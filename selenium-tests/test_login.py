# test_login.py
from selenium.webdriver.common.by import By
from base_test import BaseTest
import config
import time

class TestLogin(BaseTest):

    # Skenario 1: Tes Login Berhasil (Menggunakan Akun Admin)
    def test_login_sukses_admin(self):
        self.driver.get(config.BASE_URL)
        
        # Cari kolom HTML bernama "email", lalu ketikkan email admin
        self.driver.find_element(By.NAME, "email").send_keys(config.ADMIN_EMAIL)
        
        # Cari kolom HTML bernama "password", lalu ketikkan password admin
        self.driver.find_element(By.NAME, "password").send_keys(config.ADMIN_PASSWORD)
        
        # Klik Tombol Login
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        # Validasi: Pastikan masuk ke halaman utama/dashboard (mencari kata 'Dashboard')
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "Dashboard" in halaman_teks, "Harusnya berhasil login, tapi kata 'Dashboard' tidak ditemukan!"

    # Skenario 2: Tes Login Berhasil (Menggunakan Akun User)
    def test_login_sukses_user(self):
        self.driver.get(config.BASE_URL)
        
        # Cari kolom HTML bernama "email", lalu ketikkan email user
        self.driver.find_element(By.NAME, "email").send_keys(config.USER_EMAIL)
        
        # Cari kolom HTML bernama "password", lalu ketikkan password user
        self.driver.find_element(By.NAME, "password").send_keys(config.USER_PASSWORD)
        
        # Klik Tombol Login
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        # Validasi: Pastikan masuk ke halaman menu (mencari kata 'Menu')
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "Menu" in halaman_teks, "Harusnya berhasil login, tapi kata 'Menu' tidak ditemukan!"

    # Skenario 3: Tes Login Gagal (Password Salah)
    def test_login_gagal_password_salah(self):
        self.driver.get(config.BASE_URL)
        
        # Cari kolom HTML bernama "email", lalu ketikkan email user
        self.driver.find_element(By.NAME, "email").send_keys(config.USER_EMAIL)
        
        # Cari kolom HTML bernama "password", lalu ketikkan password yang sengaja disalahkan
        self.driver.find_element(By.NAME, "password").send_keys("password_salah_123")
        
        # Klik Tombol Login
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        # Validasi: Pastikan muncul pesan error
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "salah" in halaman_teks.lower() or "invalid" in halaman_teks.lower(), "Harusnya muncul pesan error login gagal!"