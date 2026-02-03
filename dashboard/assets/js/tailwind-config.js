// Tailwind CSS Configuration
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#ff8021",
                "background-light": "#fcfaf8",
                "danger": "#ef4444",
                "warning": "#f59e0b",
                "success": "#22c55e",
            },
            fontFamily: {
                "sans": ["Inter", "Noto Sans Thai", "sans-serif"]
            },
            borderRadius: {
                "DEFAULT": "0.25rem", 
                "lg": "0.5rem", 
                "xl": "0.75rem", 
                "full": "9999px"
            },
        },
    },
}