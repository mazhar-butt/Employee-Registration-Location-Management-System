<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <!-- Language Buttons -->
    <div class="language-buttons mb-4 text-center">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        اردو
      </button>
    </div>

    <!-- Search Bar -->
    <div class="search-container mb-6 text-center">
      <input
        type="text"
        v-model="searchQuery"
        @input="filterCountries"
        :placeholder="translations.search_placeholder || 'Search...'"
        class="search-input"
      />
    </div>

    <!-- Table and Pagination -->
    <h1 class="text-2xl font-bold mb-6 text-center">{{ translations.countries_list || 'Countries List' }}</h1>
    <button @click="navigateTo('detail')" class="edit-button mb-4">{{ translations.back || 'Back' }}</button>

    <table class="w-full table-auto border-collapse border border-gray-300 shadow-lg rounded-lg">
      <!-- Table Head -->
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
            {{ translations.country || 'Country Name' }}
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
      <!-- Table Body -->
      <tbody>
        <tr
          v-for="country in sortedAndPaginatedCountries"
          :key="country.id"
          class="hover:bg-gray-100 transition"
        >
          <td class="border border-gray-300 px-4 py-2 text-center">{{ country.id }}</td>
          <td class="border border-gray-300 px-4 py-2">
            <template v-if="editedCountry.id === country.id">
              <input
                v-model="editedCountry.name"
                class="w-full px-2 py-1 border border-gray-300 rounded"
                :placeholder="translations.enter_country_name || 'Enter country name'"
              />
            </template>
            <template v-else>
              {{ country.name }}
            </template>
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            {{ formatDate(country.created_at) }}
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            {{ formatDate(country.updated_at) }}
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            <template v-if="editedCountry.id === country.id">
              <button 
                @click="saveEdit(country.id)" 
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
                @click="editCountry(country)" 
                class="bg-blue-500 text-white"
              >
                {{ translations.edit || 'Edit' }}
              </button>
              <button 
                @click="confirmDelete(country.id)" 
                class="bg-red-500 text-white ml-2"
              >
                {{ translations.delete || 'Delete' }}
              </button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
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
    initialCountries: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      countries: [...this.initialCountries],
      filteredCountries: [...this.initialCountries], // Filtered list for search
      editedCountry: { id: null, name: "" },
      loading: false,
      translations: {},
      currentLanguage: 'en',
      sortColumn: 'id',
      sortOrder: 'asc',
      currentPage: 1,
      itemsPerPage: 7,
      searchQuery: "", 
    };
  },
  computed: {
    sortedCountries() {
      return this.filteredCountries.sort((a, b) => {
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
    sortedAndPaginatedCountries() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.sortedCountries.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredCountries.length / this.itemsPerPage);
    },
  },
  methods: {
    filterCountries() {
      const query = this.searchQuery.trim().toLowerCase();
      
      if (query === "") {
        this.filteredCountries = [...this.countries];
      } else {
        this.filteredCountries = this.countries.filter(country =>
          country.name.toLowerCase().includes(query)
        );
      }

      // Reset pagination
      this.currentPage = 1;
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
    editCountry(country) {
      this.cancelEdit();
      this.editedCountry = { ...country };
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
      if (!this.editedCountry.name.trim()) {
        Swal.fire({
          title: this.translations.error || 'Error',
          text: this.translations.country_name_empty || 'Country name cannot be empty',
          icon: 'error'
        });
        return;
      }

      this.loading = true;

      try {
        const response = await axios.put(`/countries/${id}`, {
          name: this.editedCountry.name.trim()
        });
        
        Swal.fire({
          title: this.translations.success || 'Success!',
          text:  this.translations.country_updated || 'Country updated successfully',
          icon: 'success',
          confirmButtonText: this.translations.ok_button_text || 'OK'
        }).then(() => {
          const index = this.countries.findIndex(c => c.id === id);
          if (index !== -1) {
            this.countries[index] = {
              ...this.countries[index],
              name: this.editedCountry.name.trim(),
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
      this.editedCountry = { id: null, name: "" };
    },
    async confirmDelete(id) {
      const country = this.countries.find((c) => c.id === id);

      const result = await Swal.fire({
        title: this.translations.confirm_delete || "Are you sure?",
        text: `${this.translations.delete_country || 'Do you want to delete'} "${country.name}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: this.translations.confirm_button_text || "Yes, delete it!",
        cancelButtonText: this.translations.cancel_button_text || "Cancel"
      });

      if (result.isConfirmed) {
        this.deleteCountry(id);
      }
    },
    async deleteCountry(id) {
      try {
        const response = await axios.delete(`/countries/${id}`);
        
        Swal.fire({
          title: this.translations.deleted_message || 'Deleted!',
          text: response.data.message || this.translations.country_deleted || 'Country deleted successfully',
          icon: 'success'
        }).then(() => {
          window.location.reload();
        });
      } catch (error) {
        if (error.response?.status === 400) {
          Swal.fire({
            title: this.translations.error || 'Error',
            text: this.translations.cannot_delete || 'Cannot delete this country',
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

