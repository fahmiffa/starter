export const layout = () => {
    return {
        sidebarOpen: true,
        init() {
            this.sidebarOpen = localStorage.getItem("sidebarOpen") === "true";
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem("sidebarOpen", this.sidebarOpen);
        },
        toggleSidebarMobile() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeSidebarOnMobile() {
            if (window.innerWidth < 768) {
                this.sidebarOpen = false;
            }
        },
    };
};

export const dataTable = (data) => {
    return {
        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: data,

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortColumn = column;
                this.sortAsc = true;
            }
        },

        filteredData() {
            let temp = this.rows.filter((row) =>
                Object.values(row).some((val) =>
                    String(val)
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                )
            );

            temp.sort((a, b) => {
                let valA = a[this.sortColumn];
                let valB = b[this.sortColumn];

                if (typeof valA === "string") valA = valA.toLowerCase();
                if (typeof valB === "string") valB = valB.toLowerCase();

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
        },
    };
};

export function imageCropper() {
    return {
        imageUrl: null,
        cropper: null,

        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.imageUrl = URL.createObjectURL(file);

            this.$nextTick(() => {
                const image = document.getElementById("preview");
                if (this.cropper) this.cropper.destroy();
                this.cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: true,
                    aspectRatio: 1, 
                    zoomable: false,
                    rotatable: false,
                    cropBoxResizable: false,
                    background: false,
                    ready() {
                        const cropper = this.cropper;
                        const containerData = cropper.getContainerData();
                        cropper.setCropBoxData({
                            width: 200,
                            height: 200,
                            left: (containerData.width - 200) / 2,
                            top: (containerData.height - 200) / 2,
                        });
                    },
                });
            });
        },

        cropImage() {
            if (!this.cropper) return;

            this.cropper.getCroppedCanvas().toBlob((blob) => {
                // Buat file untuk dikirim ke server
                const file = new File([blob], "cropped.png", {
                    type: "image/png",
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.croppedInput.files = dataTransfer.files;

                // Tampilkan hasil crop sebagai preview
                const croppedUrl = URL.createObjectURL(blob);
                this.imageUrl = croppedUrl;

                // Hancurkan cropper dan bersihkan
                this.cropper.destroy();
                this.cropper = null;
            }, "image/png");
        },
    };
}
