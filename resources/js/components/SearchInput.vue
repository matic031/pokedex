<template>
    <v-container>
        <v-combobox ref="combobox" :items="pokemonList.map(pokemon => pokemon.name)" v-model="selectedPokemon"
            label="Type to search pokemon..." hide-no-data hide-details density="comfortable" rounded="lg"
            variant="outlined" persistent-hint clearable :loading="loading" @input="onInputChange"
            prepend-inner-icon="mdi-magnify"></v-combobox>
    </v-container>
</template>

<script>
import { fetchPokemonList } from './utils/apiService';

export default {
    name: 'SearchInput',
    data() {
        return {
            selectedPokemon: null,
            pokemonList: [],
            loading: false,
        };
    },
    watch: {
        selectedPokemon(newValue) {
            const selectedItem = this.pokemonList.find(item => item.name === newValue);
            if (selectedItem) {
                console.log('Selected Pokemon:', selectedItem.name);
                this.$emit('pokemon-selected', selectedItem.url);
            } else if (newValue === null) {
                this.$emit('pokemon-selected', null);
            }
        }
    },
    methods: {
        async loadPokemonList() {
            this.loading = true;
            try {
                this.pokemonList = await fetchPokemonList();
                this.$emit('pokemon-list-updated', this.pokemonList);
                console.log(this.pokemonList);
            } catch (error) {
                console.error('Error fetching Pokemon list:', error);
            } finally {
                this.loading = false;
            }
        },
        selectPokemonByName(name) {
            this.selectedPokemon = name;
        },
        onInputChange(newValue) {
            if (newValue === null) {
                this.selectedPokemon = null;
                this.$emit('pokemon-selected', null);
            }
        }
    },
    mounted() {
        this.loadPokemonList();
    }
};
</script>
