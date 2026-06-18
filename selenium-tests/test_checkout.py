# test_checkout.py
from selenium.webdriver.common.by import By
from base_test import BaseTest
import config
import time

class TestCheckout(BaseTest):

    def login_dan_ke_menu(self):
        self.driver.get(config.BASE_URL)
        self.driver.find_element(By.NAME, "email").send_keys(config.USER_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys(config.USER_PASSWORD)
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        self.driver.get(f"{config.BASE_URL}/menus") 
        time.sleep(2)

    def test_k1_satu_item_valid(self):
        self.login_dan_ke_menu()
        
        # Cari semua tombol '+' di halaman
        tombol_tambah = self.driver.find_elements(By.CSS_SELECTOR, ".btn-tambah")
        
        # Klik tombol '+' pada menu pertama sebanyak 2 kali (qty: 2)
        tombol_tambah[0].click()
        tombol_tambah[0].click()
        
        self.driver.find_element(By.XPATH, "//button[contains(text(), 'CHECKOUT SEKARANG')]").click()
        time.sleep(2)
        
        # Validasi: Redirect ke Halaman Pembayaran
        assert "Pembayaran" in self.driver.page_source, "K1 Gagal: Sistem tidak memproses pesanan!"

    # K2: Multiple item, qty berbeda-beda
    def test_k2_multiple_item(self):
        self.login_dan_ke_menu()
        tombol_tambah = self.driver.find_elements(By.CSS_SELECTOR, ".btn-tambah")
        
        # Asumsi ada minimal 3 menu di halaman
        tombol_tambah[0].click() # Menu 1, qty: 1
        
        for _ in range(3): 
            tombol_tambah[1].click() # Menu 2, qty: 3
            
        for _ in range(2): 
            tombol_tambah[2].click() # Menu 3, qty: 2
            
        self.driver.find_element(By.XPATH, "//button[contains(text(), 'CHECKOUT SEKARANG')]").click()
        time.sleep(2)
        
        assert "Pembayaran" in self.driver.page_source, "K2 Gagal: Gagal memproses multiple item!"

    # K3: Mix qty (Ada yang 0, ada yang > 0)
    def test_k3_mix_qty(self):
        self.login_dan_ke_menu()
        tombol_tambah = self.driver.find_elements(By.CSS_SELECTOR, ".btn-tambah")
        
        # Biarkan menu 1 tetap 0, hanya isi menu 2
        tombol_tambah[1].click()
        tombol_tambah[1].click() # Menu 2, qty: 2
        
        self.driver.find_element(By.XPATH, "//button[contains(text(), 'CHECKOUT SEKARANG')]").click()
        time.sleep(2)
        
        assert "Pembayaran" in self.driver.page_source, "K3 Gagal: Gagal memproses mix qty!"


    # K4: Semua item qty = 0
    def test_k4_semua_nol(self):
        self.login_dan_ke_menu()
        
        # Langsung klik checkout tanpa menambahkan menu apapun
        self.driver.find_element(By.XPATH, "//button[contains(text(), 'CHECKOUT SEKARANG')]").click()
        time.sleep(2)
        
        # Validasi: Muncul error minimal 1 menu
        halaman_teks = self.driver.page_source
        assert "Pilih minimal 1 menu" in halaman_teks, "K4 Gagal: Sistem bisa checkout keranjang kosong!"

    # K5: Field pesanan tidak dikirim (Simulasi bypass HTML)
    def test_k5_field_hilang(self):
        self.login_dan_ke_menu()
        
        # TRIK: Hapus paksa semua input pesanan menggunakan JavaScript
        self.driver.execute_script("document.querySelectorAll('input[name^=\"pesanan\"]').forEach(el => el.remove());")
        
        self.driver.find_element(By.XPATH, "//button[contains(text(), 'CHECKOUT SEKARANG')]").click()
        time.sleep(2)
        
        # Validasi: Memastikan user tidak dialihkan ke halaman pembayaran
        url_sekarang = self.driver.current_url
        assert "pembayaran" not in url_sekarang.lower(), "K5 Gagal: Sistem kebobolan dan masuk ke halaman pembayaran tanpa data pesanan!"