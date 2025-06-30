export const layout = () => {
    return {
        sidebarOpen: true,
        init() {
            this.sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        },
        toggleSidebarMobile() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeSidebarOnMobile() {
            if (window.innerWidth < 768) {
                this.sidebarOpen = false;
            }
        }
    };
};

export const dataTable = () => {
    return {
        search: '',
        sortColumn: 'name',
        sortAsc: true,
        currentPage: 1,
        perPage: 5,
        rows: [
            { id: 1, name: 'Alice', email: 'alice@example.com', age: 30 },
            { id: 2, name: 'Bob', email: 'bob@example.com', age: 25 },
            { id: 3, name: 'Charlie', email: 'charlie@example.com', age: 35 },
            { id: 4, name: 'David', email: 'david@example.com', age: 28 },
            { id: 5, name: 'Eve', email: 'eve@example.com', age: 22 },
            { id: 6, name: 'Frank', email: 'frank@example.com', age: 29 },
            { id: 7, name: 'Grace', email: 'grace@example.com', age: 27 },
            { id: 8, name: 'Heidi', email: 'heidi@example.com', age: 33 },
        ],

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortColumn = column;
                this.sortAsc = true;
            }
        },

        filteredData() {
            let temp = this.rows.filter(row =>
                Object.values(row).some(val =>
                    String(val).toLowerCase().includes(this.search.toLowerCase())
                )
            );

            temp.sort((a, b) => {
                let valA = a[this.sortColumn];
                let valB = b[this.sortColumn];

                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();

                if (valA < valB) return this.sortAsc ? -1 : 1;
                if (valA > valB) return this.sortAsc ? 1 : -1;
                return 0;
            });

            return temp;
        },

        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredData().slice(start, start + this.perPage);
        },

        totalPages() {
            return Math.ceil(this.filteredData().length / this.perPage);
        },

        nextPage() {
            if (this.currentPage < this.totalPages()) this.currentPage++;
        },

        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        }
    };
}
