<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <div class="language-buttons mb-4 text-center bg-black">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2">
        اردو
      </button>
    </div>

    <form @submit.prevent="submitForm">
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      <label for="state">{{ translations.select_state || 'Select State:' }}</label>
      <select v-model="selectedState" required>
        <option value="" disabled>{{ translations.select_state_placeholder || 'Select a State' }}</option>
        <option v-for="state in states" :key="state.id" :value="state.name">{{ state.name }}</option>
      </select>
      <button type="submit" :disabled="!selectedState">{{ translations.next || 'Next' }}</button>
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
  props: {
    countryId: { type: Number, required: true },
    userId: { type: Number, required: true },
  },
  setup(props) {
    const selectedState = ref("");
    const states = ref([]);
    const error = ref("");
    const translations = ref({});
    const currentLanguage = ref('en'); // Default to English

    const fetchStates = async () => {
      try {
        const response = await axios.get(`/states/${props.countryId}`);
        states.value = response.data;
      } catch (err) {
        error.value = translations.value.error_load_states || "Failed to load states."; // Default if translation not available
      }
    };

    const fetchTranslations = async (locale) => {
      try {
        const response = await axios.get(`/translations/${locale}`);
        translations.value = response.data;
        currentLanguage.value = locale; // Update current language
      } catch (error) {
        console.error("Error fetching translations:", error);
      }
    };

    const switchLanguage = (lang) => {
      fetchTranslations(lang);
      localStorage.setItem('preferredLanguage', lang); // Store the selected language in local storage
    };

    const submitForm = async () => {
      try {
        const response = await axios.post("/store-state", {
          state: selectedState.value,
          country_id: props.countryId,
          user_id: props.userId,
        });
        if (response.data.success) {
          window.location.href = `/city/${response.data.state_id}/${props.countryId}/${props.userId}`;
        }
      } catch (err) {
        error.value = translations.value.error_store_state || "Failed to store state."; // Default if translation not available
      }
    };

    // Fetch initial data
    onMounted(async () => {
      const storedLanguage = localStorage.getItem('preferredLanguage') || 'en'; // Get preferred language
      await fetchTranslations(storedLanguage); // Fetch translations for the stored language
      await fetchStates();
    });

    return { selectedState, states, error, translations, submitForm, switchLanguage, currentLanguage };
  },
};
</script>

<style>
/* Add any necessary styles */
</style>