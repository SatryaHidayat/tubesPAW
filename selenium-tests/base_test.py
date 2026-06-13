# base_test.py
import pytest
from selenium import webdriver

class BaseTest:
    
    # @pytest.fixture(autouse=True) artinya fungsi ini akan otomatis 
    # dijalankan setiap kali ada pengujian baru yang dimulai.
    @pytest.fixture(autouse=True)
    def setup_and_teardown(self):
        # ==========================================
        # FASE SETUP (Sebelum tes dimulai)
        # ==========================================
        self.driver = webdriver.Chrome()          # Membuka Google Chrome
        self.driver.maximize_window()             # Memaksimalkan ukuran layar browser
        self.driver.implicitly_wait(10)           # Memberi toleransi waktu 10 detik jika web loading lama
        
        # Kata 'yield' ini adalah batas. Saat sampai di sini, 
        # browser akan dibiarkan menyala, lalu file pengujian (seperti test_login.py) 
        # akan mengambil alih dan menjalankan skenario klik-kliknya.
        yield 
        
        # ==========================================
        # FASE TEARDOWN (Setelah tes selesai)
        # ==========================================
        self.driver.quit()                        # Menutup browser secara otomatis agar RAM komputer tidak penuh