# base_test.py
import pytest
from selenium import webdriver

class BaseTest:
    
    @pytest.fixture(autouse=True)
    def setup_and_teardown(self):
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.driver.implicitly_wait(10)   

        yield 
        
        self.driver.quit()                