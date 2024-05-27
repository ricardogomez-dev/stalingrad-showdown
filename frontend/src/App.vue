<template>
  <div>
    <RouterView />
  </div>
</template>

<script setup>
  import { RouterView } from 'vue-router'
</script>

<style>
  body {
    background-color: #D6E6E8;
    font-family: 'Poppins';
    color: #1e293b;
  }

  .bg-primary {
    color: #fff;
    background-color: #D6E6E8;
  }
</style>
