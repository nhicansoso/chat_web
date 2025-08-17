document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("searchInput");

    // Thêm 1 thẻ đếm kết quả
    const resultCount = document.createElement("div");
    resultCount.id = "searchCount";
    input.parentNode.appendChild(resultCount);

    input.addEventListener("input", () => {
        const keyword = input.value.toLowerCase().trim();
        let firstMatch = null;
        let matchCount = 0;

        document.querySelectorAll(".message-text").forEach(p => {
            const text = p.textContent.toLowerCase();
            if (keyword && text.includes(keyword)) {
                matchCount++;
                if (!firstMatch) firstMatch = p;
            }
        });

        // Cập nhật số kết quả
        resultCount.textContent = keyword ? `${matchCount} kết quả` : '';

        // Cuộn tới đoạn đầu tiên nếu có
        if (firstMatch) {
            firstMatch.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    });
});