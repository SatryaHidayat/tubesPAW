# test_admin.py
from selenium.webdriver.common.by import By
from base_test import BaseTest
import config
import time

class TestAdmin(BaseTest):

    def test_login_sebagai_admin(self):
        # Buka URL web lokal Anda
        self.driver.get(config.BASE_URL)
        
        # 1. Cari kolom Email lalu isi dengan email admin
        # (Catatan: Ganti 'email' sesuai dengan atribut name/id di HTML web Anda)
        kolom_email = self.driver.find_element(By.NAME, "email")
        kolom_email.send_keys(config.ADMIN_EMAIL)
        
        # 2. Cari kolom Password lalu isi dengan password admin
        kolom_password = self.driver.find_element(By.NAME, "password")
        kolom_password.send_keys(config.ADMIN_PASSWORD)
        
        # 3. Klik tombol submit/login
        tombol_login = self.driver.find_element(By.XPATH, "//button[@type='submit']")
        tombol_login.click()
        
        # Jeda 2 detik
        time.sleep(2)
        
        # 4. Validasi bahwa Admin berhasil masuk (Contoh: mencari teks 'Dashboard Admin')
        # Anda bisa menyesuaikan "Dashboard Admin" dengan teks asli di web Anda
        body_text = self.driver.find_element(By.TAG_NAME, "body").text
        assert "Dashboard" in body_text, "Login Admin Gagal!"