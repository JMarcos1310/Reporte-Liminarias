// Configuración inicial
document.addEventListener('DOMContentLoaded', function() {
    // Deshabilitar inspección
    disableInspection();
    
    // Inicializar validación del formulario
    initFormValidation();
    
    // Configurar el botón de ubicación
    document.getElementById('btn-ubicacion').addEventListener('click', obtenerUbicacion);
    
    // Analizar fondo y ajustar colores
    analyzeBackgroundAndAdjustColors();
});

// Función para analizar el fondo y ajustar colores
function analyzeBackgroundAndAdjustColors() {
    // Esta es una implementación básica - puede mejorarse con análisis de imagen real
    const isDarkBackground = true; // Cambiar según análisis real del fondo
    
    if (isDarkBackground) {
        document.documentElement.style.setProperty('--color-texto', '#f8f9fa');
        document.documentElement.style.setProperty('--color-fondo', 'rgba(44, 62, 80, 0.88)');
        document.documentElement.style.setProperty('--color-borde', 'rgba(255, 255, 255, 0.2)');
    } else {
        document.documentElement.style.setProperty('--color-texto', '#2c3e50');
        document.documentElement.style.setProperty('--color-fondo', 'rgba(255, 255, 255, 0.88)');
        document.documentElement.style.setProperty('--color-borde', 'rgba(0, 0, 0, 0.1)');
    }
}

// [El resto de las funciones permanecen igual que en tu versión original]