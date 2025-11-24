// Typing Effect for Hero Section

class TypeWriter {
    constructor(element, words, wait = 3000) {
        this.element = element;
        this.words = words;
        this.text = '';
        this.wordIndex = 0;
        this.wait = parseInt(wait, 10);
        this.isDeleting = false;
        this.type();
    }

    type() {
        // Current index of word
        const current = this.wordIndex % this.words.length;
        // Get full text of current word
        const fullText = this.words[current];

        // Check if deleting
        if (this.isDeleting) {
            // Remove character
            this.text = fullText.substring(0, this.text.length - 1);
        } else {
            // Add character
            this.text = fullText.substring(0, this.text.length + 1);
        }

        // Insert text into element
        this.element.innerHTML = `<span class="typing-cursor">${this.text}</span>`;

        // Type speed
        let typeSpeed = 100;

        if (this.isDeleting) {
            typeSpeed /= 2;
        }

        // If word is complete
        if (!this.isDeleting && this.text === fullText) {
            // Make pause at end
            typeSpeed = this.wait;
            // Set delete to true
            this.isDeleting = true;
        } else if (this.isDeleting && this.text === '') {
            this.isDeleting = false;
            // Move to next word
            this.wordIndex++;
            // Pause before start typing
            typeSpeed = 500;
        }

        setTimeout(() => this.type(), typeSpeed);
    }
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    const typingElement = document.getElementById('typingText');
    
    if (typingElement) {
        const words = [
            'Welcome to Inclusive Rwanda',
            'Building Inclusive Communities',
            'Empowering Lives Together',
            'Creating Equal Opportunities'
        ];
        
        // Initialize typing effect
        new TypeWriter(typingElement, words, 2000);
    }
});