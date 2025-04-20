<template>
    <div :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
        <div class="language-buttons">
            <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }">English</button>
            <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }">اردو</button>
        </div>
        <div :class="{ 'text-right': currentLanguage === 'ur' }">
            <h2>{{ translations.employee_details || 'Employee Details' }}</h2>
            <p>{{ translations.employee_list || 'List of Employee Registered in our Company.' }}</p>


            <div class="edit-buttons-container">
                <input 
            type="text" 
            v-model="searchQuery" 
            :placeholder="translations.search_placeholder || 'Search...'" 
            class="search-input"
        />
                    </div><div style=" margin-top: -19px;">
                <button @click="navigateTo('countries')" class="edit-button">{{ translations.edit_country || 'Edit Country' }}</button>
                <button @click="navigateTo('states')" class="edit-button">{{ translations.edit_state || 'Edit State' }}</button>
                <button @click="navigateTo('cities')" class="edit-button">{{ translations.edit_city || 'Edit City' }}</button>
       
            </div><br>

            <button @click="navigateTo('location')" class="edit-button">{{ translations.add_location || 'Add Country/State/City' }}</button>
            <button @click="navigateTo('register')" class="edit-button">{{ translations.register_employee || 'Register new Employee' }}</button>
            <br><br>

            <div v-if="loading">{{ translations.loading || 'Loading...' }}</div>
            <div v-else-if="error">{{ error }}</div>
            <table v-else :class="{ 'text-right': currentLanguage === 'ur' }">
           <thead>
                <tr>
                    <th 
                    @click="sortTable('username')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.username || 'Username' }}
                    <span v-if="sortColumn === 'username'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('dob')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.date_of_birth || 'Date of Birth' }}
                    <span v-if="sortColumn === 'dob'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('country')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.country || 'Country' }}
                    <span v-if="sortColumn === 'country'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('state')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.state || 'State' }}
                    <span v-if="sortColumn === 'state'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('city')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.city || 'City' }}
                    <span v-if="sortColumn === 'city'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('income')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.income || 'Income' }}
                    <span v-if="sortColumn === 'income'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('gender')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.gender || 'Gender' }}
                    <span v-if="sortColumn === 'gender'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('address')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.address || 'Address' }}
                    <span v-if="sortColumn === 'address'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th 
                    @click="sortTable('phone')" 
                    class="cursor-pointer hover:bg-gray-200"
                    >
                    {{ translations.phone_no || 'Phone No' }}
                    <span v-if="sortColumn === 'phone'">
                        {{ sortOrder === 'asc' ? '↑' : '↓' }}
                    </span>
                    </th>
                    <th>{{ translations.edit || 'Edit' }}</th>
                    <th>{{ translations.delete || 'Delete' }}</th>
                </tr>
            </thead>
                <tbody>
                    <tr v-for="detail in paginatedDetails" :key="detail.id">
                        <td>{{ detail?.user?.username || 'N/A' }}</td>
                        <td>{{ formatDate(detail?.dob) }}</td>
                        <td>{{ detail?.country?.name || 'N/A' }}</td>
                        <td>{{ detail?.state?.name || 'N/A' }}</td>
                        <td>{{ detail?.city?.name || 'N/A' }}</td>
                        <td>${{ formatIncome(detail?.income) }}</td>
                        <td>{{ capitalize(detail?.gender || '') }}</td>
                        <td>{{ detail?.address || 'N/A' }}</td>
                        <td>{{ detail?.phone || 'N/A' }}</td>
                        <td>
                            <button @click="editDetail(detail.id)" class="edit-button">{{ translations.edit || 'Edit' }}</button>
                        </td>
                        <td>
                            <button @click="confirmDelete(detail.id)" class="delete-button">{{ translations.delete || 'Delete' }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <button 
                    @click="prevPage" 
                    :disabled="currentPage === 1" 
                    class="pagination-button"
                >
                    {{ translations.previous || 'Previous' }}
                </button>
                <span class="page-info">
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
    </div>
</template>


<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    data() {
  return {
    details: [],
    translations: {},
    sortOrder: 'asc', 
    sortColumn: 'username', 
    loading: true,
    error: null,
    currentLanguage: 'en',
    currentPage: 1,
    itemsPerPage: 7,
    searchQuery: '',
  };
},
computed: {
    sortedDetails() {
        if (!Array.isArray(this.details)) return [];

        let filteredDetails = this.details.filter(detail => {
            const searchLower = this.searchQuery.toLowerCase();
            return (
                (detail?.user?.username?.toLowerCase().includes(searchLower)) ||
                (detail?.dob?.toLowerCase().includes(searchLower)) ||
                (detail?.country?.name?.toLowerCase().includes(searchLower)) ||
                (detail?.state?.name?.toLowerCase().includes(searchLower)) ||
                (detail?.city?.name?.toLowerCase().includes(searchLower)) ||
                (detail?.income?.toString().includes(searchLower)) ||
                (detail?.gender?.toLowerCase().includes(searchLower)) ||
                (detail?.address?.toLowerCase().includes(searchLower)) ||
                (detail?.phone?.toLowerCase().includes(searchLower))
            );
        });

        return [...filteredDetails].sort((a, b) => {
            let valueA, valueB;

            // Handle nested properties (e.g., user.username, country.name)
            switch (this.sortColumn) {
                case 'username':
                    valueA = a?.user?.username || '';
                    valueB = b?.user?.username || '';
                    break;
                case 'dob':
                    valueA = a?.dob || '';
                    valueB = b?.dob || '';
                    break;
                case 'country':
                    valueA = a?.country?.name || '';
                    valueB = b?.country?.name || '';
                    break;
                case 'state':
                    valueA = a?.state?.name || '';
                    valueB = b?.state?.name || '';
                    break;
                case 'city':
                    valueA = a?.city?.name || '';
                    valueB = b?.city?.name || '';
                    break;
                case 'income':
                    valueA = parseFloat(a?.income || 0);
                    valueB = parseFloat(b?.income || 0);
                    break;
                case 'gender':
                    valueA = a?.gender || '';
                    valueB = b?.gender || '';
                    break;
                case 'address':
                    valueA = a?.address || '';
                    valueB = b?.address || '';
                    break;
                case 'phone':
                    valueA = a?.phone || '';
                    valueB = b?.phone || '';
                    break;
                default:
                    valueA = '';
                    valueB = '';
            }

            // Handle numeric sorting for income
            if (this.sortColumn === 'income') {
                return this.sortOrder === 'asc' ? valueA - valueB : valueB - valueA;
            }

            // Handle string sorting for other columns
            if (valueA < valueB) {
                return this.sortOrder === 'asc' ? -1 : 1;
            }
            if (valueA > valueB) {
                return this.sortOrder === 'asc' ? 1 : -1;
            }
            return 0;
        });
    },

        paginatedDetails() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.sortedDetails.slice(start, end);
        },  
        totalPages() {
            return Math.ceil(this.sortedDetails.length / this.itemsPerPage);
        },
    },
    methods: {
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

        async fetchTranslations(locale) {
            this.loading = true;
            this.error = null;
            try {
                const response = await axios.get(`/translations/${locale}`);
                this.translations = response.data;
                this.currentLanguage = locale; // Update current language
            } catch (error) {
                console.error("Error fetching translations:", error);
                this.error = "Failed to load translations.";
            } finally {
                this.loading = false;
            }
        },
        switchLanguage(lang) {
        this.currentLanguage = lang; // Update current language
        localStorage.setItem('preferredLanguage', lang); // Store the selected language in local storage
        this.fetchTranslations(lang);
    },
        formatIncome(income) {
            if (income === null || income === undefined) return '0.00';
            const numIncome = parseFloat(income);
            return isNaN(numIncome) ? '0.00' : numIncome.toFixed(2);
        },
        formatDate(date) {
            if (!date) return 'N/A';
            try {
                return new Date(date).toISOString().split('T')[0];
            } catch {
                return 'N/A';
            }
        },
        capitalize(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        },
        sortTable(column) {
    if (this.sortColumn === column) {
      // Toggle sorting order if the same column is clicked
      this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
    } else {
      // Sort by the new column in ascending order by default
      this.sortColumn = column;
      this.sortOrder = 'asc';
    }
  },
        navigateTo(routeName) {
            try {
                this.$router.push({ name: routeName });
            } catch (error) {
                console.error("Navigation error:", error);
                window.location.href = `/${routeName}`;
            }
        },
        editDetail(id) {
            if (!id) return;
            window.location.href = `/details/${id}/edit`;
        },
        async fetchDetails() {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await axios.get('/details');
                const data = response.data;
                
                if (!Array.isArray(data)) {
                    throw new Error('Invalid data format received from server');
                }
                
                this.details = data;
            } catch (error) {
                console.error("Error fetching details:", error);
                this.error = "Failed to load employee details. Please try again later.";
                this.details = [];
            } finally {
                this.loading = false;
            }
        },
       confirmDelete(id) {
            if (!id) return;
            
            Swal.fire({
                title: this.translations.confirm_delete || "Are you sure?",
                text: this.translations.revert_warning || "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: this.translations.confirm_button_text || "Yes, delete it!",
                cancelButtonText: this.translations.cancel_button_text || "Cancel" 

            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteDetail(id);
                }
            });
        },

     async deleteDetail(id) {
            try {
                await axios.delete(`/details/${id}`);
                await Swal.fire(this.translations.deleted_message || "Deleted!", this.translations.delete_success || "Deleted!", "success", {
                    confirmButtonText: this.translations.ok_button_text || "OK" // Add this line
                });
                await this.fetchDetails();
            } catch (error) {
                console.error("Error deleting record:", error);
                await Swal.fire("Error!", this.translations.delete_error || "Failed to delete the record.", "error");
            }
        },
    },
    mounted() {
    // Check local storage for the preferred language
    const storedLanguage = localStorage.getItem('preferredLanguage');
    if (storedLanguage) {
        this.currentLanguage = storedLanguage; // Set the current language from local storage
        this.fetchTranslations(storedLanguage); // Fetch translations for the stored language
    } else {
        this.fetchTranslations('en'); // Default to English if no preference is set
    }
    
    this.fetchDetails(); // Fetch details regardless of language
},
    errorCaptured(err, vm, info) {
        console.error('Error captured:', err, info);
        this.error = "An error occurred while rendering the component.";
        return false;
    }
};
</script>
