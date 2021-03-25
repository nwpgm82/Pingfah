<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="/Pingfah/css/user_topbar.css">
    <title>Document</title>
</head>
<body>
    <div id="user">
        <div class="user-topbar">
            <div class="user-logo-box">
                <img src="/Pingfah/img/logo.png" alt="logo" class="user-logo">
                <div @click="burgerShow">
                    <div class="burger"></div>
                    <div class="burger"></div>
                    <div class="burger"></div>
                </div>
            </div>
            <ul id="ulShow">
                <a :href="i.link" v-for="(i,index) in menulist" :key="index"><li id="i.id">{{i.text}}</li></a>
            </ul>
        </div>
    </div>
    <script src="/Pingfah/js/user_topbar.js"></script>
</body>
</html>