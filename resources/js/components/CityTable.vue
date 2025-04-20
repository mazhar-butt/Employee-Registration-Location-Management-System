<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <div class="language-buttons mb-4 text-center">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        اردو
      </button>
    </div>
    
    <h1 class="text-2xl font-bold mb-6 text-center">{{ translations.cities_list || 'Cities List' }}</h1>
    
    <div class="search-container">
      <input 
        v-model="searchQuery" 
        type="text" 
        class="search-input" 
        :placeholder="translations.search_placeholder || 'Search City...'"
        @input="filterCities"
      />
     
    </div>

    <button @click="navigateTo('detail')" class="edit-button mb-4">{{ translations.back || 'Back' }}</button>
    <br><br>
    
    <table class="w-full table-auto border-collapse border border-gray-300 shadow-lg rounded-lg">
      <thead class="bg-gray-200">
        <tr>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('id')"
          >
            {{ translations.id || 'ID' }}
            <span v-if="sortColumn === 'id'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('name')"
          >
            {{ translations.city || 'City Name' }}
            <span v-if="sortColumn === 'name'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('created_at')"
          >
            {{ translations.created_at || 'Created At' }}
            <span v-if="sortColumn === 'created_at'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('updated_at')"
          >
            {{ translations.updated_at || 'Updated At' }}
            <span v-if="sortColumn === 'updated_at'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th class="border border-gray-300 px-4 py-2">{{ translations.actions || 'Actions' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="city in sortedAndPaginatedCities" :key="city.id" class="hover:bg-gray-100 transition">
          <td class="border border-gray-300 px-4 py-2 text-center">{{ city.id }}</td>
          <td class="border border-gray-300 px-4 py-2">
            <template v-if="editedCity.id === city.id">
              <input
                v-model="editedCity.name"
                class="w-full px-2 py-1 border border-gray-300 rounded"
                :placeholder="translations.enter_city_name || 'Enter city name'"
              />
            </template>
            <template v-else>
              {{ city.name }}
            </template>
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            {{ formatDate(city.created_at) }}
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            {{ formatDate(city.updated_at) }}
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            <template v-if="editedCity.id === city.id">
              <button 
                @click="saveEdit(city.id)" 
                :disabled="loading"
                class="bg-green-500 text-white"
              >
                {{ loading ? translations.saving || 'Saving...' : translations.save || 'Save' }}
              </button>
              <button 
                @click="cancelEdit" 
                :disabled="loading"
                class="bg-gray-500 text-white ml-2"
              >
                {{ translations.cancel_button_text || 'Cancel' }}
              </button>
            </template>
            <template v-else>
              <button 
                @click="editCity(city)" 
                class="bg-blue-500 text-white"
              >
                {{ translations.edit || 'Edit' }}
              </button>
              <button 
                @click="confirmDelete(city.id)" 
                class="bg-red-500 text-white ml-2"
              >
                {{ translations.delete || 'Delete' }}
              </button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>

    <hr>
    <div class="pagination-container">
      <button 
        @click="prevPage" 
        :disabled="currentPage === 1" 
        class="pagination-button"
      >
        {{ translations.previous || 'Previous' }}
      </button>
      <span class="pagination-text">
        {{ translations.page || 'Page' }} {{ currentPage }} {{ translations.of || 'of' }} {{ totalPages }}
      </span>
      <button 
        @click="nextPage" 
        :disabled="currentPage === totalPages" 
        class="pagination-button"
      >
        {{ translations.next || 'Next' }}
      </button>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import axios from "axios";

export default {
  props: {
    initialCities: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      searchQuery: "", // Search input binding
      cities: [...this.initialCities], // Full list of cities
      filteredCities: [...this.initialCities], // Filtered list for search
      editedCity: { id: null, name: "" },
      loading: false,
      translations: {},
      currentLanguage: 'en',
      sortColumn: 'id',
      sortOrder: 'asc',
      currentPage: 1,
      itemsPerPage: 7,
    };
  },
  computed: {
    sortedCities() {
      return this.filteredCities.sort((a, b) => {
        const valueA = a[this.sortColumn];
        const valueB = b[this.sortColumn];

        if (valueA < valueB) {
          return this.sortOrder === 'asc' ? -1 : 1;
        }
        if (valueA > valueB) {
          return this.sortOrder === 'asc' ? 1 : -1;
        }
        return 0;
      });
    },
    sortedAndPaginatedCities() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.sortedCities.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredCities.length / this.itemsPerPage);
    },
  },
  methods: {
    filterCities() {
      const query = this.searchQuery.trim().toLowerCase();
      
      // Live search logic
      if (query === "") {
        this.filteredCities = [...this.cities];
      } else {
        this.filteredCities = this.cities.filter(city =>
          city.name.toLowerCase().includes(query)
        );
      }

      // Reset pagination
      this.currentPage = 1;
    },
    performSearch() {
      // Perform search logic (same as filterCities)
      this.filterCities();
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    sortTable(column) {
      if (this.sortColumn === column) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortColumn = column;
        this.sortOrder = 'asc';
      }
    },
    formatDate(date) {
      return date ? new Date(date).toLocaleDateString() : "N/A";
    },
    switchLanguage(lang) {
      this.currentLanguage = lang;
      localStorage.setItem('preferredLanguage', lang);
      this.fetchTranslations(lang);
    },
    async fetchTranslations(locale) {
      try {
        const response = await axios.get(`/translations/${locale}`);
        this.translations = response.data;
        this.currentLanguage = locale;
      } catch (error) {
        console.error("Error fetching translations:", error);
      }
    },
    editCity(city) {
      this.cancelEdit();
      this.editedCity = { ...city };
    },
    navigateTo(routeName) {
      try {
        this.$router.push({ name: routeName });
      } catch (error) {
        console.error("Navigation error:", error);
        window.location.href = `/${routeName}`;
      }
    },
    async saveEdit(id) {
      if (!this.editedCity.name.trim()) {
        Swal.fire({
          title: this.translations.error || 'Error',
          text: this.translations.city_name_empty || 'City name cannot be empty',
          icon: 'error'
        });
        return;
      }

      this.loading = true;

      try {
        const response = await axios.put(`/cities/${id}`, {
          name: this.editedCity.name.trim()
        });
        
        Swal.fire({
          title: this.translations.success || 'Success!',
          text: this.translations.city_updated || 'City updated successfully',
          icon: 'success',
          confirmButtonText: this.translations.ok_button_text || 'OK'
        }).then(() => {
          const index = this.cities.findIndex(c => c.id === id);
          if (index !== -1) {
            this.cities[index] = {
              ...this.cities[index],
              name: this.editedCity.name.trim(),
              updated_at: new Date().toISOString()
            };
          }
          
          this.cancelEdit();
          window.location.reload();
        });
      } catch (error) {
        if (error.response && error.response.status === 422) {
          const errors = error.response.data.errors;
          Swal.fire({
            title: this.translations.validation_error || 'Validation Error',
            text: errors.name ? errors.name[0] : this.translations.invalid_input || 'Invalid input',
            icon: 'error'
          });
        } else {
          Swal.fire({
            title: this.translations.error || 'Error',
            text: error.response?.data?.message || this.translations.an_error_occurred || 'An error occurred',
            icon: 'error'
          });
        }
      } finally {
        this.loading = false;
      }
    },
    cancelEdit() {
      this.editedCity = { id: null, name: "" };
    },
    async confirmDelete(id) {
      const city = this.cities.find((c) => c.id === id);

      const result = await Swal.fire({
        title: this.translations.confirm_delete || "Are you sure?",
        text: `${this.translations.delete_city || 'Do you want to delete'} "${city.name}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: this.translations.confirm_button_text || "Yes, delete it!",
        cancelButtonText: this.translations.cancel_button_text || "Cancel"
      });

      if (result.isConfirmed) {
        this.deleteCity(id);
      }
    },
    async deleteCity(id) {
      try {
        const response = await axios.delete(`/cities/${id}`);
        
        Swal.fire({
          title: this.translations.deleted_message || 'Deleted!',
          text: this.translations.city_deleted || 'City deleted successfully',
          icon: 'success'
        }).then(() => {
          window.location.reload();
        });
      } catch (error) {
        if (error.response?.status === 400) {
          Swal.fire({
            title: this.translations.error || 'Error',
            text: this.translations.cannot_delete || 'Cannot delete this city',
            icon: 'error'
          });
        } else {
          Swal.fire({
            title: this.translations.error || 'Error',
            text: error.response?.data?.message || this.translations.an_unexpected_error || 'An unexpected error occurred',
            icon: 'error'
          });
        }
      }
    }
  },
  mounted() {
    const storedLanguage = localStorage.getItem('preferredLanguage');
    if (storedLanguage) {
      this.currentLanguage = storedLanguage;
      this.fetchTranslations(storedLanguage);
    } else {
      this.fetchTranslations('en');
    }
  }
};
</script>

