
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('table-search');
    const pluginTableBody = document.getElementById('pluginTableBody');
    const loader = document.getElementById('plugin-loader');
    const categoryRadios = document.querySelectorAll('input[name="filter-radio"]');
    let selectedCategory = "";

    categoryRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            selectedCategory = this.value;
            updateTable(searchInput.value, selectedCategory);
        });
    });

    async function updateTable(searchTerm, categoryId) {
        try {
            pluginTableBody.innerHTML = '';

            loader.classList.remove('hidden');
            const response = await fetch(`/plugin/my_plugin/list?searchTerm=${encodeURIComponent(searchTerm)

                }&category=${categoryId}`);
            if (!response.ok) {
                throw new Error(`Error fetching plugins: ${response.statusText
                    }`);
            }
            const data = await response.json();
            // Clear existing table rows
            pluginTableBody.innerHTML = '';

            if (data.length === 0) {
                const noResultsRow = document.createElement('tr');
                
                loader.classList.add('hidden');
                noResultsRow.innerHTML = `
                <td colspan="3" class="text-center py-4 dark:text-white">No plugins found</td>
                `;
                pluginTableBody.appendChild(noResultsRow);
                
                return;
            }
            // Update table with filtered data
            data.forEach(plugin => {
                const pluginRow = document.createElement('tr');
                pluginRow.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-600');
                
                loader.classList.add('hidden');
                pluginRow.innerHTML = `
                    <th class=" ">
                        ${plugin.image_name ? `
                            <div class="bg-white/5 backdrop-blur-xl w-10 h-10 rounded-lg max-h-full overflow-hidden flex items-center bg-center bg-cover bg-no-repeat" style='background-image:url("http://localhost:8000/images/plugins/${plugin.image_name
                        }")'></div>
                        ` : ''
                    }
                    </th>
                    <th scope="row" class="px-6 ps-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${plugin.name
                    }
                    </th>
                    
                
                    <td class="flex justify-end items-center" style="padding: 8px !important;">
                         <a href="${plugin.dashboard_path}" class="py-2.5 px-5 me-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Dashboard</a>
                        <a href="${plugin.uninstall_path}" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Uninstall</a>
                    </td>
                `;
                pluginTableBody.appendChild(pluginRow);
            });
        } catch (error) {
            console.error(error);
        }
    }

    // Handle search input change event
    searchInput.addEventListener('input', function () {
        updateTable(searchInput.value, selectedCategory);
    });

    // Initial load of table (optional)
    updateTable('', '');
});
