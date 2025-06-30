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

export const dataTable = (data) => {
    return {
        search: '',
        sortColumn: 'name',
        sortAsc: true,
        currentPage: 1,
        perPage: 5,
        rows : data,

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
