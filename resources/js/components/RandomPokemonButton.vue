<template>
    <v-btn @click="fetchRandomPokemon" color="black" icon>
        <v-icon>mdi-shuffle</v-icon>
    </v-btn>
</template>

<script>
export default {
    name: 'RandomPokemonButton',
    props: {
        pokemonList: {
            type: Array,
            required: true
        }
    },
    methods: {
        fetchRandomPokemon() {
            const randomIndex = Math.floor(Math.random() * this.pokemonList.length);
            const randomPokemon = this.pokemonList[randomIndex];
            if (randomPokemon) {
                this.$emit('random-pokemon-selected', randomPokemon);
            }
        }
    }
};
</script>
