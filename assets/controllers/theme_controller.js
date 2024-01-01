
import { Controller } from '@hotwired/stimulus';

export default class ThemeController extends Controller {
    static targets = ['themeToggle'];

    // Called when the controller is connected to the DOM
    connect() {
        this.updateTheme();
       // console.log("ThemeController connected");
    }

    // Action to toggle the theme
    toggleTheme(event) {
        const isDarkMode = event.target.checked;
        this.setTheme(isDarkMode ? 'dark' : 'light');
        //console.log("Toggle theme", isDarkMode);
    }

    // Update the theme based on stored value
    updateTheme() {
        const theme = localStorage.getItem("phoenixTheme") || 'light';
        this.setTheme(theme);
    }

    // Helper method to get item from storage
    setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem("phoenixTheme", theme);
    }
}
