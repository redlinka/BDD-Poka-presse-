<?php

include 'includes/cnx.php';

include 'includes/header.php';

// check si l'utilisateur est connecté
if (!isset($_SESSION['login']) || $_SESSION['fonction'] != 'AD' && $_SESSION['fonction'] != 'CR') {
    http_response_code(403);
    echo '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center;">Vous n\'avez pas le droit d\'être ici.</div>';
    exit;
}

?>
<h1>Membres de Poka Presse</h1>
<iframe class="frame" id="acteurs" src="liste_acteurs.php" display: none;></iframe>
<iframe id="details" style="display:none;"></iframe>
<script>
document.getElementById('acteurs').addEventListener('load', function() {
    const iframe = document.getElementById('acteurs');
    try {
        const links = iframe.contentDocument.querySelectorAll('.acteur-link');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const detailsFrame = document.getElementById('details');
                detailsFrame.src = 'profil.php?id=' + encodeURIComponent(id);
                detailsFrame.style.display = 'block';
            });
        });
    } catch (e) {
    }
});
</script>


<?php include 'includes/footer.php'; ?>