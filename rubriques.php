<?php

include 'includes/cnx.php';

include 'includes/header.php';
?>

<h1>Rubriques</h1>
<div class="horizontal">
    <div class="vertical">
        <iframe class="frame" id="rubriques" src="liste_rubriques.php"></iframe>
    </div>

    <?php if (isset($_SESSION['fonction']) && in_array($_SESSION['fonction'], ['AD', 'CR'])): ?>
        <div class="vertical">
        <form action="rubriques.php" method="post">
            <label for="nom_rubrique">Nom de la rubrique :</label>
            <input type="text" id="nom_rubrique" name="nom_rubrique" required><br>
            <label for="genealogie">Rubrique </label>
            <select id="genealogie" name="genealogie" required>
                <option value="orpheline">orpheline</option>
                <option value="parente">parente</option>
                <option value="enfant">enfant</option>
            </select>
            <div id="deSelectContainer" style="display:none;">
                de
                <select id="deSelect" name="deSelect">
                    <?php
                    $res = $cnx->query("SELECT num_rubrique, nom_rubrique FROM rubrique");
                    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $row['num_rubrique'] . '">' . $row['nom_rubrique'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <script>
                const genealogie = document.getElementById('genealogie');
                const deSelectContainer = document.getElementById('deSelectContainer');
                genealogie.addEventListener('change', function() {
                    if (this.value !== 'orpheline') {
                        deSelectContainer.style.display = 'block';
                    } else {
                        deSelectContainer.style.display = 'none';
                    }
                });
                if (genealogie.value !== 'orpheline') {
                    deSelectContainer.style.display = 'block';
                }
            </script>
            <input type="submit" value="Ajouter">
        </form>
        <br>
        <form action="rubriques.php" method="post">
            <label for="rubrique">Supprimer une rubrique :</label>
            <select id="rubrique" name="rubrique" required>
                <option value="">-- Sélectionnez une rubrique --</option>
                <?php
                $res = $cnx->query("SELECT num_rubrique, nom_rubrique FROM rubrique");
                while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $row['num_rubrique'] . '">' . $row['nom_rubrique'] . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Supprimer">
        </form>
        </div>
    <?php endif; ?>
</div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['nom_rubrique'])) {
            try {
                $cnx->beginTransaction();
                $cnx->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");
                // Vérifier si la rubrique existe
                $stmt = $cnx->prepare("SELECT COUNT(*) FROM rubrique WHERE nom_rubrique = :nom_rubrique");
                $stmt->execute([':nom_rubrique' => $_POST['nom_rubrique']]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception('Cette rubrique existe déjà.');

                } elseif (isset($_POST['genealogie']) && $_POST['genealogie'] == 'orpheline') {
                    $stmt = $cnx->prepare("INSERT INTO rubrique (nom_rubrique,num_rubrique_ancetre) VALUES (:nom_rubrique, NULL)");
                    $stmt->execute([':nom_rubrique' => $_POST['nom_rubrique']]);

                } elseif (isset($_POST['genealogie']) && $_POST['genealogie'] == 'parente') {
                    $stmt = $cnx->prepare("INSERT INTO rubrique (nom_rubrique,num_rubrique_ancetre) VALUES (:nom_rubrique, NULL)");
                    $stmt->execute([':nom_rubrique' => $_POST['nom_rubrique']]);

                    $lastId = $cnx->lastInsertId();
                    $stmt = $cnx->prepare("UPDATE rubrique SET num_rubrique_ancetre = :num_rubrique_ancetre WHERE num_rubrique = :num_rubrique");
                    $stmt->execute([':num_rubrique_ancetre' => $lastId, ':num_rubrique' => $_POST['deSelect']]);

                } elseif (isset($_POST['genealogie']) && $_POST['genealogie'] == 'enfant') {
                    $stmt = $cnx->prepare("INSERT INTO rubrique (nom_rubrique,num_rubrique_ancetre) VALUES (:nom_rubrique, :num_rubrique_ancetre)");
                    $stmt->execute([':nom_rubrique' => $_POST['nom_rubrique'], ':num_rubrique_ancetre' => $_POST['deSelect']]);
                }
                $cnx->commit();

            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
                $cnx->rollBack();
                exit;
            }
            header("Refresh:0");
            echo '<p>Rubrique ajoutée avec succès !</p>';
            exit;
            
        }
    }
    ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['rubrique'])) {
            try {
                $cnx->beginTransaction();
                $cnx->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");
                // Vérifier si la rubrique existe
                $stmt = $cnx->prepare("SELECT COUNT(*) FROM rubrique WHERE num_rubrique = :num_rubrique");
                $stmt->execute([':num_rubrique' => $_POST['rubrique']]);
                if ($stmt->fetchColumn() == 0) {
                    throw new Exception('Rubrique non trouvée.');
                }
                // Suppression des rubriques enfants
                $stmt = $cnx->prepare("DELETE FROM rubrique WHERE num_rubrique_ancetre = :num_rubrique");
                $stmt->execute([':num_rubrique' => $_POST['rubrique']]);

                $stmt = $cnx->prepare("DELETE FROM rubrique WHERE num_rubrique = :num_rubrique");
                $stmt->execute([':num_rubrique' => $_POST['rubrique']]);
                $cnx->commit();
            } catch (Exception $e) {
                if ($e->getMessage() === 'Rubrique non trouvée.') {
                    echo 'Erreur : ' . $e->getMessage();
                } else {
                    echo 'Erreur : Un article depend de cette rubrique, vous ne pouvez pas la supprimer.';
                    exit;
                }
                $cnx->rollBack();
            }
            header("Refresh:0");  
            echo '<p>Rubrique supprimée avec succès !</p>';
            exit;
        }
    }
    ?>
<?php include 'includes/footer.php'; ?>