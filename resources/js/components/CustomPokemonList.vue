<template>
    <v-container>
        <v-row>
            <v-col v-for="pokemon in pokemons" :key="pokemon.id" cols="12" sm="6" md="6" class="d-flex align-stretch">
                <v-card class="flex-grow-1 rounded-lg pa-3">
                    <v-img :src="pokemon.image" width="150" class="rounded-circle shadow-2xl"></v-img>
                    <v-card-title>{{ pokemon.name }}</v-card-title>
                    <v-card-subtitle>{{ pokemon.description }}</v-card-subtitle>
                    <v-card-actions>
                        <v-btn icon @click="editPokemon(pokemon)">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn icon @click="deletePokemon(pokemon.id)">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <v-dialog v-model="dialog" max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Edit Pokemon data</span>
                </v-card-title>
                <v-card-text>
                    <PokemonForm :pokemonId="selectedPokemonId" :pokemon="selectedPokemon"
                        @form-saved="handleFormSaved" />
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { getCustomPokemons, deletePokemon as apiDeletePokemon } from './utils/apiService';
import PokemonForm from './PokemonForm.vue';

export default {
    name: 'CustomPokemonList',
    components: {
        PokemonForm
    },
    data() {
        return {
            pokemons: [],
            dialog: false,
            selectedPokemonId: null,
            selectedPokemon: null
        };
    },
    methods: {
        async fetchPokemons() {
            this.pokemons = await getCustomPokemons();
        },
        editPokemon(pokemon) {
            this.selectedPokemonId = pokemon.id;
            this.selectedPokemon = pokemon;
            this.dialog = true;
        },
        async deletePokemon(id) {
            await apiDeletePokemon(id);
            this.$emit('pokemon-deleted');
            if (this.$refs.customPokemonList) {
                this.fetchPokemons(); // refresh after deleting only if list is visible
            }
        },
        handleFormSaved(updatedPokemon) {
            this.dialog = false;
            this.fetchPokemons(); // refresh after update
        }
    },
    mounted() {
        this.fetchPokemons();
    }
};
</script>
