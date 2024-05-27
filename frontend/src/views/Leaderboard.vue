<template>
  <div class="py-20 px-36">
    <RouterLink to="/">
      <div class="text-2xl text-white font-bold absolute left-10 top-5 cursor-pointer">
        < Main Menu
      </div>
    </RouterLink>

    <div class="py-10 px-20 bg-white rounded-lg">
      <h2 class="text-3xl text-center text-gray-700 mb-10">
        Leaderboard
      </h2>
      <div class="mt-10">
        <table class="w-full">
          <thead class="text-left">
            <th>Player</th>
            <th>Score</th>
          </thead>
          <tbody>
            <tr v-for="player in leaderboard" class="text-lg">
              <td class="py-2 border-b">
                <div class="flex items-center">
                  <img class="w-10 h-10 rounded-full" :src="`https://ui-avatars.com/api/?background=D6E6E8&color=fff&name=${player.name}`">

                  <span class="ml-3">
                    {{ player.name }}
                  </span>
                </div>
              </td>
              <td class="py-2 border-b">
                {{ player.highest_score }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';

  const leaderboard = ref([]);

  onMounted(() => {
    axios.get('http://localhost:8010/get-leaderboard')
      .then(response => leaderboard.value = response.data)
  })
</script>