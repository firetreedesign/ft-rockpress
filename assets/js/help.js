!(function (e, t, n) {
  function a() {
    var e = t.getElementsByTagName("script")[0],
      n = t.createElement("script");
    (n.type = "text/javascript"),
      (n.async = !0),
      (n.src = "https://beacon-v2.helpscout.net"),
      e.parentNode.insertBefore(n, e);
  }
  if (
    ((e.Beacon = n = function (t, n, a) {
      e.Beacon.readyQueue.push({ method: t, options: n, data: a });
    }),
    (n.readyQueue = []),
    "complete" === t.readyState)
  )
    return a();
  e.attachEvent
    ? e.attachEvent("onload", a)
    : e.addEventListener("load", a, !1);
})(window, document, window.Beacon || function () {});

window.Beacon("init", "93088d45-1d39-4102-ae81-1a983a725c2d");

Beacon("prefill", {
  name: rockpress_beacon_vars.customer_name,
  email: rockpress_beacon_vars.customer_email,
  "RockPress Version": rockpress_beacon_vars.rockpress_ver,
  "WordPress Version": rockpress_beacon_vars.wp_ver,
  "PHP Version": rockpress_beacon_vars.php_ver,
});

Beacon("config", {
  hideFABLabelOnMobile: true,
  enableFabAnimation: false,
});
