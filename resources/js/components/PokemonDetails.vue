<template>
    <div class="pokemon-details flex p-4">
        <div v-if="loading" class="flex justify-center items-center w-full">
            <v-progress-circular indeterminate color="black" size="64"></v-progress-circular>
        </div>
        <div v-else-if="pokemon" class="flex w-full">
            <!-- left part -->
            <div class="left-side flex flex-col items-center mr-8">
                <div class="flex items-center mb-4">
                    <img width :src="currentSprite" class="w-80 h-80 rounded-full" />
                </div>
                <div class="text-center mt-4">
                    <p style="text-transform: uppercase;">{{ currentGeneration }}</p>
                </div>
                <div class="flex mt-4">
                    <div class="flex items-center mb-4" style="font-size: 1.5rem;">
                        <v-icon class="mr-2">mdi-weight</v-icon>
                        {{ (pokemon.weight / 10).toFixed(1) }} kg
                    </div>
                    <div class="flex items-center mb-4 ml-8" style="font-size: 1.5rem;">
                        <v-icon class="mr-2">mdi-ruler</v-icon>
                        {{ (pokemon.height / 10).toFixed(1) }} m
                    </div>
                </div>
            </div>
            <!-- right part -->
            <div class="right-side flex flex-col flex-1 ml-4">
                <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-bold mb-2">{{ pokemon.name }}</h2>
                    <div class="flex">
                        <span v-for="type in pokemon.types" :key="type.slot"
                            class="bg-gray-200 rounded-full px-3 py-1 mr-2">
                            {{ type.type.name }}
                        </span>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Abilities</h3>
                    <ul>
                        <li v-for="ability in pokemon.abilities" :key="ability.ability.name" class="mb-2">
                            <strong>{{ ability.ability.name }}:</strong> <span>{{ ability.effect }}</span>
                        </li>
                    </ul>
                </div>
                <div class="flex items-center mt-4">
                    <button @click="prevSprite" class="p-2 bg-gray-200 rounded-full">
                        <v-icon style="font-size: 2rem;">mdi-chevron-left</v-icon>
                    </button>
                    <button @click="nextSprite" class="p-2 ml-4 bg-gray-200 rounded-full">
                        <v-icon style="font-size: 2rem;">mdi-chevron-right</v-icon>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { fetchPokemonData, fetchDetailedAbilities } from './utils/apiService';

export default {
    name: 'PokemonDetails',
    props: {
        url: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            pokemon: null,
            sprites: [],
            generations: [],
            currentSpriteIndex: 0,
            loading: false,
        };
    },
    computed: {
        currentSprite() {
            return this.sprites[this.currentSpriteIndex];
        },
        currentGeneration() {
            return this.generations[this.currentSpriteIndex];
        },
    },
    watch: {
        url: {
            immediate: true,
            handler(newUrl) {
                this.loadPokemonData(newUrl);
            },
        },
    },
    methods: {
        async loadPokemonData(url) {
            this.loading = true;
            try {
                this.pokemon = await fetchPokemonData(url);
                console.log('Selected Pokemon data:', this.pokemon);

                this.pokemon.abilities = await fetchDetailedAbilities(this.pokemon.abilities);

                const { spriteList, generationList } = this.extractSprites(this.pokemon.sprites);
                this.sprites = spriteList;
                this.generations = generationList;
                this.currentSpriteIndex = 0;
            } catch (error) {
                console.error('Error fetching Pokemon data:', error);
            } finally {
                this.loading = false;
            }
        },
        extractSprites(sprites) {
            const spriteList = [];
            const generationList = [];

            // get default sprite first
            if (sprites.front_default) {
                spriteList.push(sprites.front_default);
                generationList.push('front default');
            }

            // get front_default sprites
            const versions = sprites.versions;
            for (const genKey in versions) {
                const generation = versions[genKey];
                for (const gameKey in generation) {
                    const game = generation[gameKey];
                    if (game.front_default) {
                        spriteList.push(game.front_default);
                        generationList.push(genKey);
                    }
                }
            }

            return { spriteList, generationList };
        },
        prevSprite() {
            this.currentSpriteIndex =
                (this.currentSpriteIndex - 1 + this.sprites.length) % this.sprites.length;
        },
        nextSprite() {
            this.currentSpriteIndex =
                (this.currentSpriteIndex + 1) % this.sprites.length;
        },
    },
};
</script>
