/**
 * Application Router - Point d'entrée principal
 * Gère l'initialisation des modules selon la page courante
 */

import { initLogin } from './modules/login.js';
import { initRegister } from './modules/register.js';
import { initDashboard } from './modules/dashboard.js';
import { initReservation } from './modules/reservation.js';
import { initAdmin } from './modules/admin.js';
import { initPayPalButton } from './modules/utils/paypal.js'; // Chemin corrigé

class AppRouter {
  constructor() {
    this.currentPage = this.getCurrentPage();
    this.modules = new Map();
    this.initializeModules();
  }

  /**
   * Récupère l'identifiant de la page courante
   * @returns {string} L'identifiant de la page
   */
  getCurrentPage() {
    const page = document.body.dataset.page;
    
    if (!page) {
      console.warn('Aucun attribut data-page trouvé sur le <body>');
      return 'unknown';
    }
    
    return page;
  }

  /**
   * Initialise les modules disponibles
   */
  initializeModules() {
    this.modules.set('login', () => this.safeInit(initLogin, 'Module Login'));
    this.modules.set('register', () => this.safeInit(initRegister, 'Module Register'));
    this.modules.set('dashboard_user', () => this.safeInit(initDashboard, 'Module Dashboard'));
    this.modules.set('nouvelle_reservation', () => this.initReservationPage());
    this.modules.set('mes_reservations', () => this.initReservationPage());
    this.modules.set('paiement', () => this.initPaymentPage()); // Ajout de la page paiement
    this.modules.set('admin', () => this.safeInit(initAdmin, 'Module Admin'));
  }

  /**
   * Initialise un module avec gestion d'erreur
   * @param {Function} initFunction - Fonction d'initialisation du module
   * @param {string} moduleName - Nom du module pour les logs
   */
  safeInit(initFunction, moduleName) {
    try {
      if (typeof initFunction === 'function') {
        initFunction();
        console.log(`✅ ${moduleName} initialisé avec succès`);
      } else {
        console.warn(`⚠️ ${moduleName} n'est pas une fonction valide`);
      }
    } catch (error) {
      console.error(`❌ Erreur lors de l'initialisation de ${moduleName}:`, error);
    }
  }

  /**
   * Initialise les pages de réservation avec gestion PayPal
   */
  initReservationPage() {
    this.safeInit(initReservation, 'Module Réservation');
    this.initPayPalIfPresent();
  }

  /**
   * Initialise spécifiquement la page de paiement
   */
  initPaymentPage() {
    console.log('🎯 Initialisation de la page de paiement');
    this.initPayPalIfPresent();
  }

  /**
   * Initialise PayPal si le conteneur est présent et valide
   */
  initPayPalIfPresent() {
    const paypalContainer = document.getElementById('paypal-button-container');
    
    if (!paypalContainer) {
      console.log('ℹ️ Aucun conteneur PayPal trouvé sur cette page');
      return;
    }

    const montantStr = paypalContainer.dataset.montant;
    
    if (!montantStr) {
      console.warn('⚠️ Conteneur PayPal trouvé mais aucun montant spécifié');
      return;
    }

    const montant = this.parseAmount(montantStr);
    
    if (montant === null) {
      console.error('❌ Montant PayPal invalide:', montantStr);
      return;
    }

    try {
      initPayPalButton(montant);
      console.log(`✅ PayPal initialisé avec le montant: ${montant}€`);
    } catch (error) {
      console.error('❌ Erreur lors de l\'initialisation de PayPal:', error);
    }
  }

  /**
   * Parse et valide un montant
   * @param {string} amountStr - Montant sous forme de chaîne
   * @returns {number|null} Le montant parsé ou null si invalide
   */
  parseAmount(amountStr) {
    const amount = parseFloat(amountStr);
    
    if (isNaN(amount) || amount <= 0) {
      return null;
    }
    
    return Math.round(amount * 100) / 100; // Arrondir à 2 décimales
  }

  /**
   * Lance l'initialisation de l'application
   */
  start() {
    console.log(`🚀 Initialisation de l'application pour la page: ${this.currentPage}`);
    
    const initFunction = this.modules.get(this.currentPage);
    
    if (initFunction) {
      initFunction();
    } else {
      console.log(`ℹ️ Aucune initialisation spécifique pour la page: ${this.currentPage}`);
    }
  }

  /**
   * Méthode utilitaire pour ajouter dynamiquement des modules
   * @param {string} pageName - Nom de la page
   * @param {Function} initFunction - Fonction d'initialisation
   */
  addModule(pageName, initFunction) {
    this.modules.set(pageName, () => this.safeInit(initFunction, `Module ${pageName}`));
  }
}

// Initialisation automatique quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
  try {
    const router = new AppRouter();
    router.start();
    
    // Expose le router globalement pour debug/extensions
    window.appRouter = router;
  } catch (error) {
    console.error('❌ Erreur critique lors de l\'initialisation de l\'application:', error);
  }
});

// Export pour utilisation en module
export default AppRouter;