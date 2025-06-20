/**
 * Utilitaires pour les requêtes HTTP
 * Simplifie les appels API avec gestion d'erreurs intégrée
 */

/**
 * Configuration par défaut pour les requêtes
 */
const DEFAULT_CONFIG = {
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'same-origin' // Inclut les cookies de session
};

/**
 * Effectue une requête HTTP avec gestion d'erreurs
 * @param {string} url - URL de la requête
 * @param {Object} options - Options de la requête
 * @returns {Promise<any>} - Données de la réponse
 */
export async function fetchData(url, options = {}) {
    try {
        // Fusion des options par défaut avec celles fournies
        const config = {
            ...DEFAULT_CONFIG,
            ...options,
            headers: {
                ...DEFAULT_CONFIG.headers,
                ...options.headers
            }
        };

        console.log(`🌐 Requête ${config.method || 'GET'} vers: ${url}`);

        const response = await fetch(url, config);

        // Vérification du statut HTTP
        if (!response.ok) {
            throw new Error(`Erreur HTTP ${response.status}: ${response.statusText}`);
        }

        // Détection du type de contenu pour parser correctement
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            console.log('✅ Réponse JSON reçue:', data);
            return data;
        } else {
            const text = await response.text();
            console.log('✅ Réponse texte reçue');
            return text;
        }

    } catch (error) {
        console.error('❌ Erreur lors de la requête:', error);
        throw error;
    }
}

/**
 * Requête GET simplifiée
 * @param {string} url - URL de la requête
 * @param {Object} headers - Headers supplémentaires
 * @returns {Promise<any>} - Données de la réponse
 */
export async function get(url, headers = {}) {
    return fetchData(url, {
        method: 'GET',
        headers
    });
}

/**
 * Requête POST simplifiée
 * @param {string} url - URL de la requête
 * @param {Object} data - Données à envoyer
 * @param {Object} headers - Headers supplémentaires
 * @returns {Promise<any>} - Données de la réponse
 */
export async function post(url, data = {}, headers = {}) {
    return fetchData(url, {
        method: 'POST',
        headers,
        body: JSON.stringify(data)
    });
}

/**
 * Requête PUT simplifiée
 * @param {string} url - URL de la requête
 * @param {Object} data - Données à envoyer
 * @param {Object} headers - Headers supplémentaires
 * @returns {Promise<any>} - Données de la réponse
 */
export async function put(url, data = {}, headers = {}) {
    return fetchData(url, {
        method: 'PUT',
        headers,
        body: JSON.stringify(data)
    });
}

/**
 * Requête DELETE simplifiée
 * @param {string} url - URL de la requête
 * @param {Object} headers - Headers supplémentaires
 * @returns {Promise<any>} - Données de la réponse
 */
export async function del(url, headers = {}) {
    return fetchData(url, {
        method: 'DELETE',
        headers
    });
}

/**
 * Envoie des données de formulaire (FormData)
 * @param {string} url - URL de la requête
 * @param {FormData} formData - Données du formulaire
 * @param {Object} headers - Headers supplémentaires
 * @returns {Promise<any>} - Données de la réponse
 */
export async function postForm(url, formData, headers = {}) {
    return fetchData(url, {
        method: 'POST',
        headers: {
            // Ne pas définir Content-Type pour FormData (le navigateur le fait automatiquement)
            'X-Requested-With': 'XMLHttpRequest',
            ...headers
        },
        body: formData
    });
}

/**
 * Utilitaire pour construire des URLs avec paramètres de requête
 * @param {string} baseUrl - URL de base
 * @param {Object} params - Paramètres à ajouter
 * @returns {string} - URL complète avec paramètres
 */
export function buildUrl(baseUrl, params = {}) {
    const url = new URL(baseUrl, window.location.origin);
    
    Object.entries(params).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
            url.searchParams.append(key, value);
        }
    });
    
    return url.toString();
}

/**
 * Gestion des erreurs réseau avec retry automatique
 * @param {Function} requestFunction - Fonction de requête à exécuter
 * @param {number} maxRetries - Nombre maximum de tentatives
 * @param {number} delay - Délai entre les tentatives (ms)
 * @returns {Promise<any>} - Résultat de la requête
 */
export async function withRetry(requestFunction, maxRetries = 3, delay = 1000) {
    let lastError;
    
    for (let attempt = 1; attempt <= maxRetries; attempt++) {
        try {
            return await requestFunction();
        } catch (error) {
            lastError = error;
            console.warn(`⚠️ Tentative ${attempt}/${maxRetries} échouée:`, error.message);
            
            if (attempt < maxRetries) {
                console.log(`⏱️ Nouvelle tentative dans ${delay}ms...`);
                await new Promise(resolve => setTimeout(resolve, delay));
                delay *= 2; // Backoff exponentiel
            }
        }
    }
    
    throw lastError;
}

/**
 * Wrapper pour les requêtes avec indicateur de chargement
 * @param {Function} requestFunction - Fonction de requête
 * @param {string} loadingElementId - ID de l'élément de chargement
 * @returns {Promise<any>} - Résultat de la requête
 */
export async function withLoading(requestFunction, loadingElementId = null) {
    const loadingElement = loadingElementId ? document.getElementById(loadingElementId) : null;
    
    try {
        if (loadingElement) {
            loadingElement.style.display = 'block';
        }
        
        const result = await requestFunction();
        return result;
        
    } finally {
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }
}