<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!1,baseUrl:""},contact:{enabled:!0,formId:"65c982c9-e34c-11e6-8789-0a5fecc78a4d"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>

HS.beacon.config({
  modal: false,
  color: '#ff5555',
  topics: rockpress_beacon_vars.topics,
  instructions: 'Please fill out this form and RockPress Support will get back to you as soon as possible.',
  attachment: false,
  poweredBy: false,
  icon: 'message',
});

HS.beacon.ready(function() {
  HS.beacon.identify({
    name: rockpress_beacon_vars.customer_name,
    email: rockpress_beacon_vars.customer_email,
    'RockPress Version' : rockpress_beacon_vars.ccbpress_ver,
    'WordPress Version' : rockpress_beacon_vars.wp_ver,
    'PHP Version' : rockpress_beacon_vars.php_ver,
  });
});
