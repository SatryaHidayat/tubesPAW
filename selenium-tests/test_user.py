# test_user.py
from selenium.webdriver.common.by import By
from base_test import BaseTest
import config
import time

class TestUser(BaseTest):

    def test_login_sebagai_user(self):
        # Buka URL web lokal Anda
        self.driver.get(config.BASE_URL)
        
        # 1. Masukkan email user
        kolom_email = self.driver.find_element(By.NAME, "email")
        kolom_email.send_keys(config.USER_EMAIL)
        
        # 2. Masukkan password user
        kolom_password = self.driver.find_element(By.NAME, "password")
        kolom_password.send_keys(config.USER_PASSWORD)
        
        # 3. Klik tombol submit/login
        tombol_login = self.driver.find_element(By.XPATH, "//button[@type='submit']")
        tombol_login.click()
        
        # Jeda 2 detik
        time.sleep(2)
        
        # 4. Validasi bahwa User berhasil masuk (Contoh: mencari teks 'Menu Kafe')
        body_text = self.driver.find_element(By.TAG_NAME, "body").text
        assert "Menu" in body_text, "Login User Gagal!"