<template>
    <v-container>
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field v-model="form.name" label="Name" :rules="[
                value => !!value || 'Name is required',
                value => value.length <= 50 || 'Name must be less than 50 characters',
                value => /^[^0-9]*$/.test(value) || 'Name must not contain numbers'
            ]" required rounded="lg" variant="outlined"></v-text-field>
            <v-textarea v-model="form.description" label="Description" :rules="[
                value => !!value || 'Description is required',
                value => value.length <= 300 || 'Description must be less than 300 characters'
            ]" required rounded="lg" variant="outlined" class="mt-2"></v-textarea>
            <v-file-input v-model="form.imageFile" label="Image"
                accept="image/png, image/jpeg, image/jpg, image/svg+xml" :rules="[
                    value => !!value || 'Image is required',
                    value => !value || !value.some(value => value.size > 1e6) || 'Image size should be less than 1 MB'
                ]" required rounded="lg" variant="outlined" class="mt-2"></v-file-input>
            <div v-if="form.image && !form.imageFile" class="mt-2 mb-8">
                <v-img :src="form.image" width="100" class="rounded-full" alt="Current Image"></v-img>
            </div>
            <v-btn :disabled="!isFormValid" @click="submit" rounded="lg" variant="outlined">Save</v-btn>
            <v-progress-circular v-if="loading" indeterminate></v-progress-circular>
        </v-form>
    </v-container>
</template>

<script>
import { addPokemon, updatePokemon } from './utils/apiService';

export default {
    name: 'PokemonForm',
    props: {
        pokemonId: {
            type: Number,
            required: false
        },
        pokemon: {
            type: Object,
            default: () => ({
                name: '',
                description: '',
                image: null
            })
        }
    },
    data() {
        return {
            form: {
                name: this.pokemon.name,
                description: this.pokemon.description,
                image: this.pokemon.image,
                imageFile: null
            },
            valid: false,
            loading: false
        };
    },
    computed: {
        isFormValid() {
            return this.valid && (this.form.imageFile || this.form.image);
        }
    },
    methods: {
        async submit() {
            if (this.$refs.form.validate()) {
                this.loading = true;
                try {
                    const formData = new FormData();
                    formData.append('name', this.form.name);
                    formData.append('description', this.form.description);
                    if (this.form.imageFile) {
                        formData.append('image', this.form.imageFile);
                    }

                    let response;

                    if (this.pokemonId) {
                        response = await updatePokemon(this.pokemonId, formData);
                    } else {
                        response = await addPokemon(formData);
                    }

                    this.$emit('form-saved', response);
                    this.$emit('refresh-list');
                } catch (error) {
                    console.error('Error saving data:', error);
                } finally {
                    this.loading = false;
                }
            }
        }
    },
    watch: {
        pokemon(newPokemon) {
            this.form.name = newPokemon.name;
            this.form.description = newPokemon.description;
            this.form.image = newPokemon.image;
            this.form.imageFile = null;
        }
    }
};
</script>
