Guide d’installation : Prototype de site de démocratie participative

Voici un guide d'installation pour votre prototype de site de démocratie basé sur CodeIgniter 3, en utilisant WampServer comme serveur local. Assurez-vous de suivre attentivement ces étapes:

Prérequis :
WampServer : Téléchargez et installez la version 3.2.3 de WampServer à partir de leur site officiel.

Fichier SQL : Assurez-vous d'avoir votre fichier SQL comportant la structure de la base de données ainsi qu'un jeu de données. (democratie_v2.sql)

Navigateur Web : Assurez-vous d'avoir un navigateur Web installé sur votre système (Chrome, Firefox, etc.).

Étapes d'installation :
Installation de WampServer 3.2.3 :

Téléchargez et exécutez le programme d'installation de WampServer.
Suivez les instructions à l'écran pour installer WampServer sur votre système.
Une fois l'installation terminée, lancez WampServer.
Configuration de WampServer :

Vérifiez que WampServer est en cours d'exécution. L'icône de WampServer dans la barre des tâches doit être verte.
Assurez-vous que les services Apache et MySQL sont démarrés. Vous pouvez les vérifier en cliquant avec le bouton droit de la souris sur l'icône de WampServer et en sélectionnant les options correspondantes.

Verifier les versions de PHP (7.4.9) et MySQL (5.7.31).

Importation de la base de données :

Ouvrez phpMyAdmin en cliquant avec le bouton gauche de la souris sur l'icône de WampServer, puis en sélectionnant "phpMyAdmin" dans le menu.
Connectez-vous à phpMyAdmin (le nom d'utilisateur par défaut est "root" et il n'y a pas de mot de passe par défaut).
Dans phpMyAdmin, importez le fichier SQL (democratie_v2.sql) en utilisant l'option "Importer". Vérifiez que vous avec bien une nouvelle base de donnée démocratie_v2 avec les tables correspondantes.


Installation du projet sous CodeIgniter3 :

Décompressez l'archive téléchargée et placez-la dans le répertoire wamp64/www (ou dans le répertoire où vous avez installé WampServer).
Renommez le dossier extrait en un nom approprié pour votre projet, par exemple "democratie".

Configuration de CodeIgniter :

Ouvrez le fichier application/config/database.php dans votre projet CodeIgniter.
Modifiez les paramètres de la base de données (hostname, username, password, database) pour correspondre à votre configuration MySQL.

Test de l'installation :

Ouvrez votre navigateur Web et accédez à l'adresse http://localhost/votredossierdemocratie (remplacez "votredossierdemocratie" par le nom du dossier que vous avez créé pour votre projet).
Si tout est configuré correctement, vous devriez voir la page d'accueil de votre site de démocratie.

L'identifiant et le mot de passe des utilisateurs test sont :
JohnDoe mdp: hashedPassword1 , JaneDoe mdp:hashedPassword2
