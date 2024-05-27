<template>
  <div class="h-screen flex items-center justify-center">
    <div class="w-500px py-10 px-20 bg-white rounded-lg">
      <div class="mb-5 flex justify-center">
        <div class="w-1/2">
          <img src="/src/assets/logo.png">
        </div>
      </div>
      <RouterLink to="/simulation">
        <div class="p-5 text-center bg-primary rounded-lg mb-7 font-bold cursor-pointer transform transition ease-in duration-100	hover:scale-125">
          START GAME
        </div>
      </RouterLink>
      <RouterLink to="/leaderboard">
        <div class="p-5 text-center bg-primary rounded-lg mb-7 font-bold cursor-pointer transform transition ease-in duration-100	hover:scale-125">
          LEADERBOARD
        </div>
      </RouterLink>
      <RouterLink to="/history">
        <div class="p-5 text-center bg-primary rounded-lg mb-7 font-bold cursor-pointer transform transition ease-in duration-100	hover:scale-125">
          HISTORY
        </div>
      </RouterLink>
    </div>
    <div class="texture-wrapper right">
      <img src="/src/assets/texture-left.svg" />
    </div>
    <div class="texture-wrapper left">
      <img src="/src/assets/texture-right.svg" />
    </div>
  </div>
</template>

<style scoped>
  .w-500px {
    width: 500px;
  }

  .texture-wrapper {
    max-width: 300px;
    z-index: -1;
  }

  img {
    width: 100%;
  }

  .left {
    position: absolute;
    left: 100px;
    bottom: 0;
  }

  .right {
    position: absolute;
    right: 100px;
    top: 0;
  }
</style>