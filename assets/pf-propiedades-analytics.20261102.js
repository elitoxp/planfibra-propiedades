/* Propiedades PlanFibra: GTM consent-gated, no user document content or form fields. */
(function () {
  'use strict';
  if (location.pathname !== '/' || location.search) return;
  if (window.__pfPropiedadesAnalyticsInit) return;
  window.__pfPropiedadesAnalyticsInit = true;
  var gtm = 'GTM-MXMFFNH4';
  var loaded = false;
  var privacyBlocked = function () {
    return navigator.globalPrivacyControl === true ||
      navigator.doNotTrack === '1' || window.doNotTrack === '1';
  };
  window.dataLayer = window.dataLayer || [];
  window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
  window.gtag('consent', 'default', {
    analytics_storage: 'denied',
    ad_storage: 'denied',
    ad_user_data: 'denied',
    ad_personalization: 'denied'
  });
  // Remove query strings/fragments, which may contain uploaded-file metadata.
  window.gtag('set', 'page_location', location.origin + location.pathname);
  if (document.referrer) {
    try {
      var ref = new URL(document.referrer);
      window.gtag('set', 'page_referrer', ref.origin + ref.pathname);
    } catch (_) {}
  }
  function checkConsent() {
    if (privacyBlocked()) return;
    if (!window.PFRadar || typeof window.PFRadar.preferences !== 'function') return;
    if (window.PFRadar.preferences().analytics !== 'granted') return;
    if (loaded || document.getElementById('pf-propiedades-gtm')) return;
    loaded = true;
    window.gtag('consent', 'update', {
      analytics_storage: 'granted',
      ad_storage: 'denied',
      ad_user_data: 'denied',
      ad_personalization: 'denied'
    });
    window.dataLayer.push({'gtm.start': Date.now(), event: 'gtm.js'});
    var s = document.createElement('script');
    s.async = true;
    s.id = 'pf-propiedades-gtm';
    s.src = 'https://www.googletagmanager.com/gtm.js?id=' + gtm;
    document.head.appendChild(s);
  }
  window.addEventListener('pf-consent-changed', function (event) {
    if (!event.detail || event.detail.analytics !== 'granted') {
      window.gtag('consent', 'update', {analytics_storage: 'denied'});
      return;
    }
    checkConsent();
  });
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', checkConsent, {once: true});
  } else {
    checkConsent();
  }
  window.addEventListener('pageshow', checkConsent);
})();
