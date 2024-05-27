<template>
  <div class="p-20">
    <RouterLink to="/">
      <div class="text-2xl text-white font-bold absolute left-10 top-5 cursor-pointer">
        < Main Menu
      </div>
    </RouterLink>
    <div v-if="tanks.length" class="grid grid-cols-2 gap-x-20 mb-10">
      <TankDetail :player="players[0]" :tank="tanks[0]" />
      <TankDetail :player="players[1]" :tank="tanks[1]" />
    </div>
    <div class="map-container rounded-full">
      <div v-for="(row, rowIndex) in map" :key="rowIndex" class="map-row">
        <div v-for="(cell, colIndex) in row" :key="colIndex" :id="`cell-${rowIndex}-${colIndex}`" class="flex items-center justify-center map-cell">
          <div v-if="cell == 1" class="flex justifty-center items-center">
            <img class="tank" src="/src/assets/tank.svg">
            <img v-if="attackingTank && attackingTank.row == rowIndex && attackingTank.col == colIndex" class="bullet" src="/src/assets/bullet.svg">
          </div>
          <img v-if="cell == 2" class="bomb" src="/src/assets/bomb.svg">
        </div>
      </div>
    </div>
    <PlayersSummary :players="players" :tanks="tanks" />
  </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import PriorityQueue from 'js-priority-queue';
import TankDetail from '@/components/TankDetail.vue';
import PlayersSummary from '@/components/PlayersSummary.vue';

const map = ref([]);
const tanks = ref([]);
const players = ref([]);
const backgroundAudio = ref();
const attackingTank = ref({});
const router = useRouter();

let currentTankIndex = 0;
let gameEnded = false;

const EMPTY_CELL = 0;
const TANK_CELL = 1;
const OBSTACLE_CELL = 2;

const backgroundSound = () => {
  backgroundAudio.value = new Audio('/src/assets/background.aac');
  backgroundAudio.value.play();
};

const startBackgroundSound = () => {
  for (let i = 0; i < 10; i++) {
    setTimeout(() => {
      if (gameEnded || router.currentRoute.value.path != '/simulation') return
      backgroundSound();
    }, 32000 * i)
  }
}

const attackSound = () => {
  const audio = new Audio('/src/assets/tank-fire.aac');
  audio.play();
};

const startBattle = async () => {
  try {
    const response = await axios.get('http://localhost:8010/simulate');
    map.value = response.data.map;
    tanks.value = response.data.tanks;
    players.value = response.data.players;
    initializeTanks();
    startSimulation();
    startBackgroundSound();
  } catch (error) {
    console.error('Error fetching map:', error);
  }
};

const initializeTanks = () => {
  let tankIndex = 0;
  map.value.forEach((row, rowIndex) => {
    row.forEach((cell, colIndex) => {
      if (cell === TANK_CELL) {
        tanks.value[tankIndex].id = tankIndex
        tanks.value[tankIndex].row = rowIndex
        tanks.value[tankIndex].col = colIndex
        tanks.value[tankIndex].health = 5
        tankIndex++
      }
    });
  });

  setTimeout(() => {
    const cellId = `cell-${tanks.value[0].row}-${tanks.value[0].col}`
    const cell = document.getElementById(cellId)
    cell.scrollIntoView({ behavior: 'smooth' })
  }, 1000)
};

const moveTank = (tank) => {
  const enemy = getNearestEnemy(tank);
  if (!enemy) return;

  const distanceToEnemy = Math.abs(tank.row - enemy.row) + Math.abs(tank.col - enemy.col);

  if (distanceToEnemy <= tank.turretRange) {
    console.log(`Tank ${tank.id} within turret range of enemy ${enemy.id}. Attacking instead of moving.`);
    // Attack enemy
    attackTank(tank, enemy);
  } else {
    const path = findPath(tank, enemy);

    if (path.length > 1) {
      // Move the tank based on its speed
      const moves = Math.min(tank.speed, path.length - 1); // Ensure it doesn't move beyond the path length
      const nextMove = path[moves]; // path[0] is the current position
      console.log(`Tank ${tank.id} with speed ${tank.speed} moving from (${tank.row},${tank.col}) to (${nextMove.row},${nextMove.col})`);
      
      moveToNewPosition(tank, nextMove.row, nextMove.col);

    } else {
      console.log(`Tank ${tank.id} has no valid moves`);
    }
  }
};

const attackTank = (attacker, target) => {
  console.log(`Tank ${attacker.id} attacking tank ${target.id}`);
  const direction = getDirection(attacker, target);
  console.log(`Tank ${attacker.id} fires ${direction} at tank ${target.id}`);
  target.health -= 1;
  console.log(`Tank ${target.id} health reduced to ${target.health}`);

  attackingTank.value = { row: attacker.row, col: attacker.col }
  setTimeout(() => attackingTank.value = {}, 500)
  attackSound();

  if (target.health <= 0) {
    endGame(attacker, target);
  }
};

const getDirection = (attacker, target) => {
  const dRow = target.row - attacker.row;
  const dCol = target.col - attacker.col;

  if (dRow === 0 && dCol < 0) return 'left';
  if (dRow === 0 && dCol > 0) return 'right';
  if (dRow < 0 && dCol === 0) return 'up';
  if (dRow > 0 && dCol === 0) return 'down';
  if (dRow < 0 && dCol < 0) return 'up-left';
  if (dRow < 0 && dCol > 0) return 'up-right';
  if (dRow > 0 && dCol < 0) return 'down-left';
  if (dRow > 0 && dCol > 0) return 'down-right';
};

const endGame = (winner, loser) => {
  gameEnded = true;
  backgroundAudio.value.pause();

  const player = players.value[winner.id]
  console.log(`${player.name} wins!`);

  axios.post('http://localhost:8010/add-player-to-leaderboard', { 
    name: player.name, 
    player_id: player._id.$oid, 
  })

  Swal.fire({
    title: `${player.name} wins!`,
    confirmButtonColor: '#D6E6E8',
    confirmButtonText: 'LEADERBOARD'
  }).then(() => {
    router.push('/leaderboard');
  });

  const audio = new Audio('/src/assets/game-ended.aac');
  audio.play();
};

const findPath = (tank, enemy) => {
  const rows = map.value.length;
  const cols = map.value[0].length;

  const start = { row: tank.row, col: tank.col };
  const goal = { row: enemy.row, col: enemy.col };

  const nodeToString = (node) => `${node.row},${node.col}`;

  const openList = new PriorityQueue({ comparator: (a, b) => a.f - b.f });
  const openSet = new Set();
  const closedList = new Set();
  const cameFrom = new Map();

  const gScore = Array.from({ length: rows }, () => Array(cols).fill(Infinity));
  gScore[start.row][start.col] = 0;

  const fScore = Array.from({ length: rows }, () => Array(cols).fill(Infinity));
  fScore[start.row][start.col] = heuristic(start, goal);

  openList.queue({ ...start, f: fScore[start.row][start.col] });
  openSet.add(nodeToString(start));

  const getNeighbors = (node, goal) => {
    const neighbors = [];
    const directions = [
      { row: -1, col: 0 },
      { row: 1, col: 0 },
      { row: 0, col: -1 },
      { row: 0, col: 1 },
    ];
    for (const direction of directions) {
      const neighbor = { row: node.row + direction.row, col: node.col + direction.col };
      if (
        neighbor.row >= 0 && neighbor.row < rows &&
        neighbor.col >= 0 && neighbor.col < cols &&
        (map.value[neighbor.row][neighbor.col] === EMPTY_CELL || (neighbor.row === goal.row && neighbor.col === goal.col))
      ) {
        // Ensure that the neighbor is not another tank, unless it is the goal
        if (!tanks.value.some(t => t.row === neighbor.row && t.col === neighbor.col && (neighbor.row !== goal.row || neighbor.col !== goal.col))) {
          neighbors.push(neighbor);
        }
      }
    }
    return neighbors;
  };

  while (openList.length > 0) {
    const current = openList.dequeue();
    openSet.delete(nodeToString(current));

    if (current.row === goal.row && current.col === goal.col) {
      return reconstructPath(cameFrom, current);
    }

    closedList.add(nodeToString(current));

    for (const neighbor of getNeighbors(current, goal)) {
      if (closedList.has(nodeToString(neighbor))) continue;

      const tentativeGScore = gScore[current.row][current.col] + 1;

      if (tentativeGScore < gScore[neighbor.row][neighbor.col]) {
        cameFrom.set(nodeToString(neighbor), current);
        gScore[neighbor.row][neighbor.col] = tentativeGScore;
        fScore[neighbor.row][neighbor.col] = gScore[neighbor.row][neighbor.col] + heuristic(neighbor, goal);
        if (!openSet.has(nodeToString(neighbor))) {
          openList.queue({ ...neighbor, f: fScore[neighbor.row][neighbor.col] });
          openSet.add(nodeToString(neighbor));
        }
      }
    }
  }

  return [];
};

const heuristic = (a, b) => {
  return Math.abs(a.row - b.row) + Math.abs(a.col - b.col);
};

const reconstructPath = (cameFrom, current) => {
  const totalPath = [current];
  const nodeToString = (node) => `${node.row},${node.col}`;
  while (cameFrom.has(nodeToString(current))) {
    current = cameFrom.get(nodeToString(current));
    totalPath.push(current);
  }
  return totalPath.reverse();
};

const moveToNewPosition = (tank, newRow, newCol) => {
  if (map.value[newRow][newCol] !== EMPTY_CELL) {
    console.log(`Cell (${newRow},${newCol}) is not empty. Tank ${tank.id} cannot move there.`);
    return;
  }
  map.value[tank.row][tank.col] = EMPTY_CELL;
  tank.row = newRow;
  tank.col = newCol;
  map.value[newRow][newCol] = TANK_CELL;
};

const getNearestEnemy = (tank) => {
  let minDistance = Infinity;
  let nearestEnemy = null;
  tanks.value.forEach((enemy) => {
    if (enemy.id !== tank.id) {
      const distance = Math.abs(tank.row - enemy.row) + Math.abs(tank.col - enemy.col);
      if (distance < minDistance) {
        minDistance = distance;
        nearestEnemy = enemy;
      }
    }
  });
  return nearestEnemy;
};

const startSimulation = () => {
  const interval = setInterval(() => {
    if (tanks.value.length <= 1 || gameEnded) {
      clearInterval(interval);
      console.log('Simulation ended');
      return;
    }
    const currentTank = tanks.value[currentTankIndex];
    moveTank(currentTank);
    currentTankIndex = (currentTankIndex + 1) % tanks.value.length;
  }, 1000);
};

onMounted(() => {
  Swal.fire({
    title: 'Prepare for Battle!',
    text: 'The time has come to test your might and strategy. Two formidable tanks are about to face off in an epic showdown. Will you emerge victorious, or will you fall in the heat of battle?',
    confirmButtonColor: '#D6E6E8',
    confirmButtonText: 'START BATTLE'
  }).then(() => {
    if (router.currentRoute.value.path == '/simulation') {
      startBattle();
    }
  });
});
</script>

<style scoped>
.map-container {
  overflow-x: auto;
  white-space: nowrap;
  border-radius: 30px;
  background-color: #fff;
}

.map-row {
  display: flex;
  width: max-content;
}

.map-cell {
  width: 75px;
  height: 75px;
  border: 1px solid #f3f4f6;
}

.tank {
  width: 100%;
  z-index: 1;
}

.bomb {
  width: 60%;
  z-index: 1;
}

.bullet {
  width: 60%;
  z-index: 100;
  margin-left: -10px;
}

.swal2-container {
  font-family: 'Poppins';
}
</style>
