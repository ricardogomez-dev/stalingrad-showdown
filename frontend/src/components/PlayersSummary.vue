<template>
  <div v-if="players.length && tanks.length" class="z-10 fixed bottom-0 right-7 shadow-lg opacity-90">
    <div @click="scrollIntoTank(tanks[index])" v-for="(player, index) in players" class="bg-white rounded-lg mb-5 p-5 cursor-pointer">
      <div class="text-xl bg-white">
        {{ player.name }}
      </div>
      <div class="text-gray-500 bg-white">
        Speed: {{ tanks[index].speed }}
      </div>
      <div class="text-gray-500 bg-white">
        Turret Range: {{ tanks[index].turretRange }}
      </div>
      <div class="text-gray-500 bg-white">
        <div class="flex items-center">
          <span class="mr-2">Health: </span>
          <img v-for="item in tanks[index].health" src="/src/assets/heart.svg">
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { defineProps } from 'vue';

  defineProps({
    players: {
      type: Array,
      required: true,
    },
    tanks: {
      type: Array,
      required: true,
    },
  });

  const scrollIntoTank = (tank) => {
    const cell = document.getElementById(`cell-${tank.row}-${tank.col}`)
    cell.scrollIntoView({ behavior: 'smooth' })
  }
</script>