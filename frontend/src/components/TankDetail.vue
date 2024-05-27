<template>
  <div @click="scrollIntoTank(tank)" class="col-span-1 bg-white rounded-lg p-7 cursor-pointer">
    <div class="flex bg-white">
      <img class="w-36 bg-white" src="/src/assets/tank.svg">
      <div class="ml-7 bg-white">
        <div class="text-xl bg-white">
          {{ player.name }}
        </div>
        <div class="text-gray-500 bg-white">
          Speed: {{ tank.speed }}
        </div>
        <div class="text-gray-500 bg-white">
          Turret Range: {{ tank.turretRange }}
        </div>
        <div class="text-gray-500 bg-white">
          Health: {{ tank.health }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { defineProps } from 'vue';

  defineProps({
    player: {
      type: Object,
      required: true,
    },
    tank: {
      type: Object,
      required: true,
    },
  });

  const scrollIntoTank = (tank) => {
    const cell = document.getElementById(`cell-${tank.row}-${tank.col}`)
    cell.scrollIntoView({ behavior: 'smooth' })
  }
</script>