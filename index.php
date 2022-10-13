<?php
require_once '_connec.php';

// Pour se connecter à la base de données et créer un nouvel objet $pdo
$pdo = new \PDO(DSN, USER, PASS);

// Pour sélectionner dans la BD et avoir un retour sous forme de tableau associatif. Nom de champs en clé et les valeurs en données.
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

// Variable déclarer pour créer un tableau répertoriant les erreurs
$errors = [];

// Je vérifie que des données ont été renvoyées par POST et j'effectue les contrôles.
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    // je créé une variable qui contiendra les données cleanées. 
    $data = array_map('trim', $_POST);

    // Je vérifie si le champ est bien là et s'il est remplit. Dans le cas contraire, je la stocke dans ma variable.     
    if(!isset($data['firstname']) || empty($data['firstname'])) 
    $errors[] = "Le prénom est obligatoire";

    if(strlen($data['firstname']) > 45)
    $errors[] = "La e prénom ne doit pas dépasser 45 caractères";

    if(!isset($data['lastname']) || empty($data['lastname'])) 
    $errors[] = "Le nom est obligatoire";

    if(strlen($data['lastname']) > 45)
    $errors[] = "Le nom ne doit pas dépasser 45 caractères";

    // Si le résultat des erreurs est égal à 0 alors je lance l'inscription dans la base de données. 
    if(count($errors) === 0) {
        // Je fais une requête de type INSERT INTO avec le nom de la table est les colonnes correspondantes. 
        $query = "INSERT INTO friend (`firstname`, `lastname`)
        -- Je met les placeholders pour faire mes tests avant.
        VALUES (:firstname, :lastname)";
        // Je demande de préparer la petite voiture et de m'attendre avant d'aller dans la BDD
        $statement = $pdo->prepare($query);
        // PDO::PARAM_STR permet de faire des tests sur une chaine de caractère
        $statement->bindValue(':firstname', $data['firstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $data['lastname'], PDO::PARAM_STR);
        // Je demande à envoyer la voiture
        $statement->execute();
        header('Location: /');
        // je mets un dis pour ne pas recharger à nouveau le formulaire
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.R.I.E.N.D.S</title>
</head>
<body>

<?php 
    if(count($errors) > 0) {
        echo '<ul>';
    foreach($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
        echo '</ul>';
}
?>
    <main>
                    <ul>
                    <?php foreach ($friends as $friend)
                    {
                    echo '<li>' . $friend['firstname'] ." ". $friend['lastname'] . '</li>';
                        }
                        ?>
    </ul>


<form action ="" method ="post" enctype="application/x-www-form-urlencoded" class="errormessages">
    <fieldset>
        <legend>Les friends</legend>

<p>
    <label for="firstname">First name :</label>
    <input type="text" id="firstname" name="firstname" placeholder="Your firstname here">
</p>

<p>
    <label for="lastname">Last name :</label>
    <input type="text" id="lastname" name="lastname" placeholder="Your lastname here">
</p>

    <button type="submit">Go to friends !</button>

    </fieldset>
</form>
    </main>
</body>
</html>
