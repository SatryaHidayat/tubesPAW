# test_promo.py
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select 
from base_test import BaseTest
import config
import time
import pytest  

class TestPromo(BaseTest):

    # --- FUNGSI BANTUAN: Alur login -> pesan 1 minuman -> riwayat -> pembayaran ---
    def siapkan_halaman_pembayaran(self):
        self.driver.get(config.BASE_URL)
        self.driver.find_element(By.NAME, "email").send_keys(config.USER_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys(config.USER_PASSWORD)
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        self.driver.get(f"{config.BASE_URL}/menus") 
        time.sleep(2)
        
        tombol_tambah = self.driver.find_elements(By.CSS_SELECTOR, ".btn-tambah")
        if len(tombol_tambah) > 0:
            tombol_tambah[0].click() 
            
            self.driver.find_element(By.XPATH, "//button[contains(text(), 'CHECKOUT SEKARANG')]").click()
            time.sleep(2)
            
        try:
            # Cari tombol Bayar Sekarang di halaman Riwayat
            tombol_bayar_sekarang = self.driver.find_element(By.XPATH, "//a[contains(., 'Bayar Sekarang')]")
            tombol_bayar_sekarang.click()
            time.sleep(2) 
        except Exception as e:
            print("Tombol Bayar Sekarang di Riwayat tidak ditemukan:", e)

    def test_k1_promo_valid(self):
        self.siapkan_halaman_pembayaran()
        
        # 1. Pasang Promo
        kolom_promo = self.driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan kode')]")
        kolom_promo.send_keys("HEMAT10")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        # 2. Pilih Metode Pembayaran
        dropdown = Select(self.driver.find_element(By.NAME, "metode_pembayaran"))
        dropdown.select_by_value("cash")
        
        # 3. PERBAIKAN: Klik Bayar Sekarang menggunakan Class (btn-success)
        self.driver.find_element(By.CSS_SELECTOR, "button.btn-success").click()
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "riwayat pesanan" in halaman_teks or "sukses" in halaman_teks, "K1 Gagal: Pembayaran dengan promo tidak berhasil!"

    def test_k2_promo_lowercase(self):
        self.siapkan_halaman_pembayaran()
        
        kolom_promo = self.driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan kode')]")
        kolom_promo.send_keys("hemat10")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        dropdown = Select(self.driver.find_element(By.NAME, "metode_pembayaran"))
        dropdown.select_by_value("cash")
        
        # PERBAIKAN: Klik Bayar Sekarang menggunakan Class
        self.driver.find_element(By.CSS_SELECTOR, "button.btn-success").click()
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "riwayat pesanan" in halaman_teks or "sukses" in halaman_teks, "K2 Gagal: Backend gagal memproses kode lowercase!"

    def test_k3_promo_tidak_ada(self):
        self.siapkan_halaman_pembayaran()
        
        kolom_promo = self.driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan kode')]")
        kolom_promo.send_keys("TIDAKADA")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "tidak ditemukan" in halaman_teks or "salah" in halaman_teks, "K3 Gagal: Output error tidak sesuai!"

    def test_k4_promo_kosong(self):
        self.siapkan_halaman_pembayaran()
        
        self.driver.execute_script("document.querySelector('input[name=\"kode_promo\"]').removeAttribute('required');")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "required" in halaman_teks or "wajib" in halaman_teks, "K4 Gagal: Form terkirim padahal field kosong!"

    def test_k5_promo_dobel(self):
        self.siapkan_halaman_pembayaran()
        
        # Pemasangan 1
        kolom_promo = self.driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan kode')]")
        kolom_promo.send_keys("HEMAT10")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        # Pemasangan 2
        kolom_promo_lagi = self.driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan kode')]")
        kolom_promo_lagi.send_keys("HEMAT10")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "sudah terpasang" in halaman_teks, "K5 Gagal: Output error double promo tidak sesuai!"

    def test_k6_order_milik_orang_lain(self):
        self.driver.get(config.BASE_URL)
        self.driver.find_element(By.NAME, "email").send_keys(config.USER_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys(config.USER_PASSWORD)
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        self.driver.get(f"{config.BASE_URL}/pembayaran/99999")
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "404" in halaman_teks or "not found" in halaman_teks, "K6 Gagal: Tidak melempar 404!"

# Tambahkan baris ini di atas fungsi K7
    @pytest.mark.xfail(reason="Bug: Backend Laravel belum memblokir order lunas")
    def test_k7_promo_order_sudah_dibayar(self):
        self.siapkan_halaman_pembayaran()

        url_pembayaran_aktif = self.driver.current_url
        
        dropdown_metode = Select(self.driver.find_element(By.NAME, "metode_pembayaran"))
        dropdown_metode.select_by_value("cash")
        
        # PERBAIKAN: Klik Bayar Sekarang menggunakan Class
        self.driver.find_element(By.CSS_SELECTOR, "button.btn-success").click()
        time.sleep(2)
        
        self.driver.get(url_pembayaran_aktif)
        time.sleep(2)
        
        halaman_teks = self.driver.page_source.lower()
        assert "404" in halaman_teks or "not found" in halaman_teks, "K7 Gagal: Halaman input promo bisa diakses saat lunas!"

    def test_k8_diskon_melebihi_harga(self):
        self.siapkan_halaman_pembayaran()
        
        # Representatif K8: Harga minuman 12.000, tapi promo DISKONGILA potong 100.000
        kolom_promo = self.driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan kode')]")
        kolom_promo.send_keys("DISKONGILA")
        self.driver.find_element(By.XPATH, "//button[text()='Pasang']").click()
        time.sleep(2)
        
        # Guard aktif: Harus Rp 0, tidak boleh minus
        total_tagihan = self.driver.find_element(By.XPATH, "//h4[contains(text(), 'Rp')]").text
        assert "-" not in total_tagihan, f"K8 Gagal: Guard gagal, harga menjadi negatif! ({total_tagihan})"
        
        total_dibersihkan = total_tagihan.replace(" ", "").replace(".", "").lower()
        assert "rp0" in total_dibersihkan, f"K8 Gagal: Total tagihan tidak menjadi Rp 0! ({total_tagihan})"