  window.fbAsyncInit = function() {
    FB.init({
      appId            : appID,
      autoLogAppEvents : true,
      xfbml            : true,
      version          : "v2.9"
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, "script", "facebook-jssdk"));
document.getElementById("mkLockerFB").onclick = function() {
  document.getElementById("mkLockerFB").innerHTML = "Wait...";
  FB.ui({
    method: "share",
    display: "popup",
    href: postURL,
    hashtag: hashTag,
  }, function(response){
    if(String(response) === "undefined"){
      document.getElementById("mkLockerFB").innerHTML = "<i class=\'icon-facebook\'></i> Share";
      alert(getFacebookAlert);
    }else{
      document.getElementById("mkSocialLocker").style.display = "none";
      document.getElementById("mkSocialLockerHideContent").style.display = "block";
    }
  });
}

document.getElementById("mkLockerTW").onclick = function() {
  window.open("https://twitter.com/home?status="+getTwitterContent, "_blank", "top=500,left=500,width=500,height=500");
  document.getElementById("mkLockerTW").innerHTML = "Wait...";
  setTimeout(function(){
    document.getElementById("mkSocialLocker").style.display = "none";
      document.getElementById("mkSocialLockerHideContent").style.display = "block";
    },5000)
}

  function callbackGoogleAction(data){
  if(data.state=="on"){
      setTimeout(function(){
      document.getElementById("mkSocialLocker").style.display = "none";
      document.getElementById("mkSocialLockerHideContent").style.display = "block";
    },2000)
  }else{
    //Oops...
  }
}