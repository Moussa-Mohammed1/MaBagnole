# 🚗 MaBagnole — Plateforme de location de voitures

MaBagnole est une application web permettant la gestion complète d’une agence de location de voitures.  
Elle offre aux **clients** la possibilité de consulter, réserver et évaluer des véhicules, et aux **administrateurs** des outils efficaces de gestion et de suivi.

---

## 📌 Contexte du projet

Ce projet s’inscrit dans le cadre de la formation **Développeur Web et Web Mobile**.  
L’objectif est de mettre en pratique :
- La programmation **PHP orientée objet**
- La gestion de base de données avec **MySQL**
- La conception **UML**
- Les bonnes pratiques de structuration d’un projet web

---

## 🎯 Objectifs

- Créer une plateforme intuitive et fonctionnelle
- Permettre la réservation de véhicules en ligne
- Gérer les utilisateurs, véhicules, catégories et avis
- Implémenter des fonctionnalités SQL avancées
- Appliquer une architecture claire et maintenable

---

## 👤 Fonctionnalités Client

- Inscription et connexion
- Consultation des catégories de véhicules
- Affichage des détails d’un véhicule
- Recherche par modèle ou caractéristiques
- Filtrage dynamique des véhicules (sans rechargement)
- Pagination de la liste des véhicules
- Réservation avec dates et lieux de prise en charge
- Ajout, modification et suppression d’avis (Soft Delete)

---

## 🛠️ Fonctionnalités Administrateur

- Gestion des véhicules
- Gestion des catégories
- Insertion en masse de véhicules ou catégories
- Gestion des réservations
- Gestion des avis clients
- Tableau de bord avec statistiques (Dashboard)

---

## 🧱 Architecture du projet

- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP (POO)
- **Base de données** : MySQL
- **Architecture** : MVC (Model – View – Controller)
- **Autoloading** : Composer

---
## 📚 Technologies utilisées

- **PHP (POO)**

- **MySQL**

- **Composer**

- **HTML / CSS / JavaScript**

- **UML**

---

## 🗄️ Base de données

Tables principales :
- `utilisateur`
- `car`
- `category`
- `reservation`
- `avis`

Concepts utilisés :
- Relations avec clés étrangères
- Soft delete
- Normalisation des données

---

## ⚙️ Fonctionnalités SQL avancées

### 📄 Vue SQL
- `ListeVehicules`
- Permet d’afficher les véhicules avec :
  - Catégories
  - Moyenne des avis
  - Disponibilité

### 🔁 Procédure stockée
- `AjouterReservation`
- Centralise la logique d’ajout d’une réservation
- Garantit la cohérence des données

---
## ✅ Statut du projet

- 🟢 En cours de développement / Version académique
