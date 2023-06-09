/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import 'tw-elements';

import {
    Collapse,
    Dropdown,
    Ripple,
    Tab,
    initTE,
} from "tw-elements";

initTE({ Collapse, Dropdown, Ripple, Tab });

import './js/euromillion_grid_limit.js'