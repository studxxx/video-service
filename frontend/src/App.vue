<template>
  <div id="app">
    <header class="app-header">
      <Nav/>
    </header>

    <div class="app-content">
      <main class="container">
        <router-view/>
      </main>
    </div>

    <footer class="app-footer">
      <div class="container">
        <hr>
        <p>&copy; App. All right reserved.</p>
      </div>
    </footer>

  </div>
</template>

<script>
  import Nav from "@/components/Nav.vue";

  export default {
    components: {
      Nav
    }
  }
</script>

<style lang="scss">
  body {
    min-height: 100vh;
  }
</style>
