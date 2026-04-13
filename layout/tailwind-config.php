<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    500: '#0891b2',
                    600: '#0e7490',
                    700: '#155e75',
                    800: '#164e63',
                    900: '#134152',
                },
                accent: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                },
            },
            animation: {
                'fade-down': 'fadeDown 0.6s ease',
                'fade-up': 'fadeUp 0.7s ease both',
                'fade-up-delay-1': 'fadeUp 0.7s ease 0.1s both',
                'fade-up-delay-2': 'fadeUp 0.7s ease 0.2s both',
                'fade-up-delay-3': 'fadeUp 0.7s ease 0.3s both',
                'fade-up-delay-4': 'fadeUp 0.7s ease 0.4s both',
                'fade-in-right': 'fadeInRight 0.8s ease 0.3s both',
                'pulse-dot': 'pulseDot 2s infinite',
            },
            keyframes: {
                fadeDown: {
                    from: { opacity: '0', transform: 'translateY(-10px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                fadeUp: {
                    from: { opacity: '0', transform: 'translateY(20px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInRight: {
                    from: { opacity: '0', transform: 'translateX(30px)' },
                    to: { opacity: '1', transform: 'translateX(0)' },
                },
                pulseDot: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.4' },
                },
            },
        },
    },
}
</script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; }
    a { text-decoration: none; color: inherit; }
</style>
