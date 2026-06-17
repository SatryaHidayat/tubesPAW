# test_login.py
from selenium.webdriver.common.by import By
from base_test import BaseTest
import config
import time

class TestLogin(BaseTest):

    def matikan_validasi_browser(self):
        script = """
        document.querySelectorAll('input').forEach(el => {
            el.removeAttribute('required');
            el.removeAttribute('minlength');
            if(el.type === 'email') el.type = 'text';
        });
        """
        self.driver.execute_script(script)


    def test_k1_login_sukses_admin(self):
        self.driver.get(config.BASE_URL)
        
        self.driver.find_element(By.NAME, "email").send_keys(config.ADMIN_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys(config.ADMIN_PASSWORD)
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "Dashboard" in halaman_teks, "K1 Gagal: Tidak bisa masuk ke Dashboard Admin!"

    def test_k2_login_gagal_no_a(self):
        self.driver.get(config.BASE_URL)
        self.matikan_validasi_browser() 
        
        self.driver.find_element(By.NAME, "email").send_keys("admincafe.com")
        self.driver.find_element(By.NAME, "password").send_keys(config.ADMIN_PASSWORD)
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text

        assert "salah" in halaman_teks.lower() or "match" in halaman_teks.lower() or "credentials" in halaman_teks.lower(), "K2 Gagal: Sistem kebobolan email tanpa @"

    def test_k3_login_gagal_no_domain(self):
        self.driver.get(config.BASE_URL)
        self.matikan_validasi_browser() 
        
        self.driver.find_element(By.NAME, "email").send_keys("admin@cafe")
        self.driver.find_element(By.NAME, "password").send_keys(config.ADMIN_PASSWORD)
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "salah" in halaman_teks.lower() or "match" in halaman_teks.lower() or "credentials" in halaman_teks.lower(), "K3 Gagal: Sistem kebobolan email tanpa domain"

    def test_k4_login_gagal_password_tidak8(self):
        self.driver.get(config.BASE_URL)
        self.matikan_validasi_browser() 
        
        self.driver.find_element(By.NAME, "email").send_keys(config.ADMIN_EMAIL) 
        self.driver.find_element(By.NAME, "password").send_keys("admin12")
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "salah" in halaman_teks.lower() or "match" in halaman_teks.lower() or "credentials" in halaman_teks.lower(), "K4 Gagal: Sistem menerima password di bawah 8 karakter"

    def test_k5_login_gagal_password_salah(self):
        self.driver.get(config.BASE_URL)
        
        self.driver.find_element(By.NAME, "email").send_keys(config.ADMIN_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys("passwordsalah")
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "salah" in halaman_teks.lower() or "match" in halaman_teks.lower() or "credentials" in halaman_teks.lower(), "K5 Gagal: Sistem tidak menolak password yang salah"

    def test_k6_login_gagal_kosong(self):
        self.driver.get(config.BASE_URL)
        self.matikan_validasi_browser() 
        
        self.driver.find_element(By.NAME, "email").send_keys("") 
        self.driver.find_element(By.NAME, "password").send_keys("") 
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()
        time.sleep(2)
        
        halaman_teks = self.driver.find_element(By.TAG_NAME, "body").text
        assert "salah" in halaman_teks.lower() or "required" in halaman_teks.lower() or "wajib" in halaman_teks.lower(), "K6 Gagal: Sistem bisa disubmit walau form kosong!"