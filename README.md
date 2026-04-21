Gestion de l’état civil – Ville de Cotonou
Description

Cette application permet de digitaliser la gestion de l’état civil de la ville de Cotonou.
Elle prend en charge l’enregistrement, la modification, la consultation et la recherche des actes de naissance, de mariage et de décès.

Le système est conçu pour être utilisé par les services d’état civil des 13 arrondissements ainsi que par l’administration centrale de la mairie.

Objectif

L’objectif est de remplacer la gestion manuelle des actes par un système informatique centralisé permettant :

une meilleure organisation des données
une recherche rapide des informations
la génération automatique des documents
un accès sécurisé selon les rôles
Fonctionnalités principales
Gestion des actes de naissance
Gestion des actes de mariage
Gestion des actes de décès
Recherche automatique des enregistrements
Génération des certificats en PDF
Gestion des utilisateurs
Statistiques (par type, période, arrondissement, utilisateur)
Gestion des accès

Le système fonctionne avec des rôles :

Administrateur
Accès complet à toutes les données
Gestion des utilisateurs
Superviseur
Peut enregistrer, modifier et consulter les actes de son arrondissement
Analyste
Peut consulter les données et les statistiques de son arrondissement

Chaque utilisateur est lié à un arrondissement et ne peut accéder qu’aux données correspondantes, sauf l’administrateur qui a un accès global.

Architecture

L’application repose sur une architecture centralisée :

une application web accessible via navigateur
un serveur central hébergeant le système
une base de données MariaDB
un accès depuis les différents arrondissements via réseau ou Internet
Technologies utilisées
PHP (sans framework)
MariaDB
HTML / CSS
JavaScript
Dompdf (génération des PDF)
