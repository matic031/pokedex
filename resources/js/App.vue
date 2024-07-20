<template>
    <v-app>
        <v-main>
            <v-container class="flex items-center">
                <SearchInput @pokemon-selected="updateSelectedPokemon" @pokemon-list-updated="updatePokemonList"
                    ref="searchInput" />
                <RandomPokemonButton class="ml-8" :pokemonList="pokemonList"
                    @random-pokemon-selected="selectRandomPokemon" />
            </v-container>

            <v-container>
                <PokemonDetails v-if="selectedPokemonUrl" :url="selectedPokemonUrl" />

                <v-divider :thickness="1" class="border-opacity-100 mt-8"></v-divider>
                <v-btn class="mt-8" @click="toggleForm" color="black" variant="outlined" large>
                    Add Your Custom Pokemon
                    <v-icon :class="{ 'rotate-180': formVisible }">mdi-chevron-down</v-icon>
                </v-btn>
                <PokemonForm v-if="formVisible" :pokemonId="selectedPokemonId" @form-saved="handleFormSaved"
                    @refresh-list="refreshCustomPokemonList" class="border-thin rounded-lg mt-4" />

                <v-divider :thickness="1" class="border-opacity-100 mt-8"></v-divider>
                <v-btn class="mt-8" @click="toggleList" color="black" variant="outlined" large>
                    Show Your Custom Pokemon
                    <v-icon :class="{ 'rotate-180': listVisible }">mdi-chevron-down</v-icon>
                </v-btn>
                <CustomPokemonList ref="customPokemonList" v-if="listVisible" @pokemon-deleted="handlePokemonDeleted"
                    class="border-thin rounded-lg mt-4" />
            </v-container>

            <v-dialog v-model="confirmationDialog" max-width="400px">
                <v-card>
                    <v-card-title class="headline">{{ confirmationTitle }}</v-card-title>
                    <v-card-text class="text-center">
                        <v-icon color="green" size="48">{{ confirmationIcon }}</v-icon>
                        {{ confirmationMessage }}
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green" text @click="confirmationDialog = false">OK</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </v-main>
    </v-app>
</template>

<script>
import SearchInput from './components/SearchInput.vue';
import RandomPokemonButton from './components/RandomPokemonButton.vue';
import PokemonDetails from './components/PokemonDetails.vue';
import PokemonForm from './components/PokemonForm.vue';
import CustomPokemonList from './components/CustomPokemonList.vue';

export default {
    name: 'App',
    components: {
        SearchInput,
        RandomPokemonButton,
        PokemonDetails,
        PokemonForm,
        CustomPokemonList
    },
    data() {
        return {
            pokemonList: [],
            selectedPokemonUrl: null,
            selectedPokemonId: null,
            formVisible: false,
            listVisible: false,
            confirmationDialog: false,
            confirmationTitle: '',
            confirmationMessage: '',
            confirmationIcon: '',
        };
    },
    methods: {
        updatePokemonList(pokemonList) {
            this.pokemonList = pokemonList;
        },
        updateSelectedPokemon(url) {
            this.selectedPokemonUrl = url;
            const selectedItem = this.pokemonList.find(pokemon => pokemon.url === url);
            if (selectedItem) {
                this.selectedPokemonId = selectedItem.id;
            }
        },
        selectRandomPokemon(pokemon) {
            this.$refs.searchInput.selectPokemonByName(pokemon.name);
            this.updateSelectedPokemon(pokemon.url);
        },
        handleFormSaved(pokemonData) {
            console.log('Form saved:', pokemonData);
            this.formVisible = false;
            this.showConfirmationDialog('Success', 'mdi-check-circle', 'Pokemon added successfully!');
            if (this.listVisible) {
                this.refreshCustomPokemonList();
            }
        },
        handlePokemonDeleted() {
            this.showConfirmationDialog('Deleted', 'mdi-check-circle', 'Pokemon deleted successfully!');
            if (this.listVisible) {
                this.refreshCustomPokemonList();
            }
        },
        toggleForm() {
            this.formVisible = !this.formVisible;
        },
        toggleList() {
            this.listVisible = !this.listVisible;
            if (this.listVisible) {
                this.refreshCustomPokemonList();
            }
        },
        refreshCustomPokemonList() {
            if (this.$refs.customPokemonList) {
                this.$refs.customPokemonList.fetchPokemons();
            }
        },
        showConfirmationDialog(title, icon, message) {
            this.confirmationTitle = title;
            this.confirmationIcon = icon;
            this.confirmationMessage = message;
            this.confirmationDialog = true;
        }
    }
};
</script>
