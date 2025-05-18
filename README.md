# Projet #2 - Application de gestion d'un parking pour voitures
**Répertoire GitLab :** `~/app-gestion-parking`

---

## 1. Contexte

L'objectif de ce projet est de concevoir une application permettant de gérer un parking destiné à des voitures. Les utilisateurs pourront :

- Réserver des places de parking.
- Consulter la disponibilité des places en temps réel.
- Gérer leurs réservations.
- Effectuer des paiements.

L'application permettra également :

- L’administration de l’occupation des places.
- La gestion des tarifs.
- L’envoi de notifications (rappels de réservation, alertes de disponibilité).
- La gestion des utilisateurs et des paramètres du parking (horaires, tarifs).

---

## 2. Contraintes techniques

### Technologies requises

- **Langages :** JavaScript, PHP, SQL
- **Architecture :** MVC (Model-View-Controller)
- **Interface :** UX/UI de qualité, conforme aux standards W3C, responsive

### Restrictions

- **Aucun framework** externe (ex. : jQuery, React, Vue.js, Laravel)
- **Structure du projet :**

#### Frontend

- HTML, CSS, JavaScript
- Programmation Orientée Objet (POO) pour JavaScript
- Architecture MVC

#### Backend

- PHP (POO) + MySQL
- Architecture MVC
- Séparation claire des responsabilités

#### Base de données

- MySQL pour gérer utilisateurs, profils, messages, abonnements
- Requêtes SQL optimisées

#### Responsive Design

- Utilisation de **media queries CSS**
- Adaptabilité aux écrans (ordinateurs, tablettes, smartphones)

#### Gestion des erreurs et logs

- Système de gestion des erreurs PHP
- Journalisation des actions utilisateurs

---

## 3. Livrables

- Code source complet (GitLab) côté front et back
- Export de la base de données en `.sql`
- **Documentation utilisateur**
- **Documentation technique**
- **Support de présentation** pour la soutenance

---

## 4. Avant de commencer

- Ce projet est **individuel**
- Vous devez choisir ce projet (ou un autre équivalent) pour votre soutenance
- Créer un dépôt GitLab nommé : `~/app-gestion-parking`
- Possibilité de réutiliser les environnements vus en cours

---

## 5. Spécifications fonctionnelles

### 5.1 Authentification

- Inscription avec nom, email, téléphone
- Connexion avec identifiants
- Gestion des comptes utilisateurs par l’administrateur :
  - Activation/Désactivation
  - Suppression

### 5.2 Gestion des places de parking

- Ajout / modification / suppression des places
- Informations associées :
  - Numéro de place
  - Type (normale, handicapée, réservée)
  - Statut (occupée, libre)
- Définition de tarifs variables selon :
  - Horaire (jour, nuit, week-end)
  - Durée de réservation
- Réservation à l'avance

### 5.3 Réservation et gestion des horaires

- Consultation en temps réel des disponibilités
- Réservation avec confirmation (email ou notification)
- Modification / annulation d’une réservation
- Respect des règles définies (durée max, heures d’ouverture)

### 5.4 Paiement et gestion des tarifs

- Paiement en ligne sécurisé (ex. : carte, PayPal)
- Calcul automatique du tarif
- Accès administrateur à :
  - Rapports de paiements
  - Revenus
  - Statistiques d’occupation

### 5.5 Notifications et rappels

- Notifications par email ou via l’application :
  - Rappels de réservation
  - Alertes de disponibilité

### 5.6 Tableau de bord personnalisé

- **Utilisateur :**
  - Réservations passées / à venir
  - Modification / annulation
- **Administrateur :**
  - Vue globale des réservations, utilisateurs, paiements, gestion des places

### 5.7 Gestion des profils utilisateurs

- Mise à jour des informations personnelles
- Préférences de paiement
- Historique des réservations et facturation
- Gestion des profils par l’administrateur :
  - Activation/Désactivation
  - Gestion des rôles

---

