document.addEventListener('DOMContentLoaded', (event) => {
    const BALL_LIMIT = 5;
    const STAR_LIMIT = 2;

    const ballsEuromillionsGrid = document.querySelector('#ballsEuromillionsGrid');
    const starsEuromillionsGrid = document.querySelector('#starsEuromillionsGrid');

    ballsEuromillionsGrid.addEventListener('change', function(event) {
        handleSelection(ballsEuromillionsGrid, BALL_LIMIT);
    });

    starsEuromillionsGrid.addEventListener('change', function(event) {
        handleSelection(starsEuromillionsGrid, STAR_LIMIT);
    });

    function handleSelection(section, limit) {
        const checkboxes = section.getElementsByTagName('input');
        const checkedCheckboxes = [...checkboxes].filter(checkbox => checkbox.checked);

        if (checkedCheckboxes.length >= limit) {
            [...checkboxes].forEach(checkbox => {
                const label = checkbox.nextElementSibling; // Accédez au label suivant la checkbox
                if (!checkbox.checked) {
                    checkbox.disabled = true;
                    label.classList.add('bg-gray-300', 'text-gray-500'); // Ajoutez les classes de Tailwind au label
                }
            });
        } else {
            [...checkboxes].forEach(checkbox => {
                const label = checkbox.nextElementSibling; // Accédez au label suivant la checkbox
                checkbox.disabled = false;
                label.classList.remove('bg-gray-300', 'text-gray-500'); // Supprimez les classes de Tailwind du label
            });
        }
    }
});