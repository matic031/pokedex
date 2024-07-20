import axios from 'axios';

const BASE_URL = 'http://localhost/api/pokemons';

export async function fetchPokemonList() {
    try {
        const response = await axios.get('https://pokeapi.co/api/v2/pokemon?limit=100');
        console.log(response);
        return response.data.results;
    } catch (error) {
        console.error('Error fetching data:', error);
        return null;
    }
}

export async function fetchPokemonData(url) {
    try {
        const response = await axios.get(url);
        console.log(response);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        return null;
    }
}

export async function fetchDetailedAbilities(abilities) {
    const abilityPromises = abilities.map(async (ability) => {
        try {
            const response = await axios.get(ability.ability.url);
            const effect = response.data.effect_entries.find(entry => entry.language.name === 'en').effect;
            return { ...ability, effect };
        } catch (error) {
            console.error('Error fetching ability details:', error);
            return { ...ability, effect: null };
        }
    });

    return Promise.all(abilityPromises);
}

export async function getCustomPokemons() {
    try {
        const response = await axios.get(`${BASE_URL}/list`);
        return response.data;
    } catch (error) {
        console.error('Error fetching custom pokemons:', error);
        return [];
    }
}

export async function addPokemon(pokemonData) {
    try {
        const response = await axios.post(BASE_URL, pokemonData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        return response.data;
    } catch (error) {
        console.error('Error adding pokemon:', error);
        return null;
    }
}

export async function updatePokemon(pokemonId, pokemonData) {
    try {
        const response = await axios.post(`${BASE_URL}/${pokemonId}`, pokemonData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        return response.data;
    } catch (error) {
        console.error('Error updating pokemon:', error);
        return null;
    }
}

export async function deletePokemon(pokemonId) {
    try {
        await axios.delete(`${BASE_URL}/${pokemonId}`);
    } catch (error) {
        console.error('Error deleting pokemon:', error);
    }
}
