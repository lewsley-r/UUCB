<template>
  <div class="home">
    <img id="uuLogo" alt="logo" src="../assets/logo_transparent.png">
    <p id="mainBio">A central archive of Ulster University Computing Projects, posts and more!</p>
    
  </div>
</template>

<script>
// @ is an alias to /src


export default {
  name: 'Home',
  components: {
  }
}
</script>


<style>
#mainBio{
  margin-top: -10vh;
}

#mainTitle{
  margin-top: 10vh;
  font-weight: 900;
  text-decoration: underline;
  text-decoration-color: #829399;
}

#uuLogo{

  height: 60vh;
  border-radius: 2em;
}

</style>


