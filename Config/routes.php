<?php
Router::connect('/donation', ['controller' => 'donation', 'action' => 'index', 'plugin' => 'donation']);
Router::connect('/donation/canceled', ['controller' => 'donation', 'action' => 'canceled', 'plugin' => 'donation']);
Router::connect('/donation/return', ['controller' => 'donation', 'action' => 'return', 'plugin' => 'donation']);
