<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Logout - Gruppo Vitolo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
        body { background: linear-gradient(135deg,rgba(255, 74, 68, 0.8) 0%,rgb(87, 35, 35) 100%); position: relative; }
        #particles-js { position: absolute; width: 100%; height: 100%; z-index: 0; }
        .logout-container { position: relative; z-index: 1; max-width: 420px; margin: auto; margin-top: 6%; padding: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 20px; backdrop-filter: blur(12px); box-shadow: 0 8px 32px rgba(0,0,0,0.25); text-align: center; }
        .logout-container img { max-width: 120px; margin-bottom: 20px; }
        a.button { display: inline-block; margin-top: 20px; padding: 12px 24px; background: #B08D57; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.3s ease, transform 0.2s; }
        a.button:hover { background: #9c7b4c; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="logout-container">
        <img src="../assets/img/logo.png" alt="Logo Gruppo Vitolo" class="logo">
        <h1>Disconnessione effettuata</h1>
        <p>Grazie per aver utilizzato il CRM.</p>
        <a class="button" href="login.php">Torna al login</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 60 },
                "color": { "value": "#ffffff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.2 },
                "size": { "value": 3 },
                "move": { "enable": true, "speed": 1.5, "direction": "none", "out_mode": "out" },
                "line_linked": { "enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.1, "width": 1 }
            },
            "interactivity": { "events": { "onhover": { "enable": true, "mode": "repulse" } } },
            "retina_detect": true
        });
    </script>
</body>
</html>
