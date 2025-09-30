<?php
// views/chamcong.php

if (!isset($nhanvienList)) $nhanvienList = [];
if (!isset($chamcongList)) $chamcongList = [];
if (!isset($editData)) $editData = null;
if (!isset($message)) $message = '';
if (!isset($error)) $error = false;
if (!isset($isAdmin)) $isAdmin = false;

@include __DIR__ . '/layouts/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Quản lý Chấm công</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{ --primary: #1d4d84; --secondary: #eaf3fd; }
        body { font-family: Inter, sans-serif; background:#f8fafc; color:#0f172a; }
    </style>
</head>
<body class="bg-gray-50 font-sans">
<main class="container mx-auto p-4 sm:p-8">

    <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-[var(--primary)] mb-4 sm:mb-0">Quản lý Chấm công</h2>
    </div>

    <?php if ($message): ?>
        <div id="alert-message" class="p-4 mb-6 rounded-lg font-medium <?= $error ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
    <!-- FORM chỉ hiện với ADMIN -->
    <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-8">
        <h3 class="text-xl sm:text-2xl font-semibold text-[var(--primary)] mb-6">
            <?= $editData ? 'Chỉnh Sửa Chấm Công' : 'Thêm Chấm Công Mới' ?>
        </h3>
        <form method="post" action="index.php?controller=chamcong&action=index">
            <?php if ($editData): ?>
                <input type="hidden" name="id_chamcong" value="<?= htmlspecialchars($editData['id_chamcong']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col">
                    <label for="id_nhanvien" class="mb-2 text-sm font-medium text-gray-700">Nhân viên:</label>
                    <select id="id_nhanvien" name="id_nhanvien" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">-- Chọn nhân viên --</option>
                        <?php foreach ($nhanvienList as $nv): ?>
                            <option value="<?= htmlspecialchars($nv['id_nhanvien']) ?>"
                                <?= $editData && $editData['id_nhanvien'] == $nv['id_nhanvien'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nv['ho_ten']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="thang" class="mb-2 text-sm font-medium text-gray-700">Tháng:</label>
                    <input type="number" id="thang" name="thang" min="1" max="12" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           value="<?= $editData ? htmlspecialchars($editData['thang']) : date('m') ?>">
                </div>

                <div class="flex flex-col">
                    <label for="nam" class="mb-2 text-sm font-medium text-gray-700">Năm:</label>
                    <input type="number" id="nam" name="nam" min="2000" max="2100" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           value="<?= $editData ? htmlspecialchars($editData['nam']) : date('Y') ?>">
                </div>

                <div class="flex flex-col">
                    <label for="so_ngay_di_lam" class="mb-2 text-sm font-medium text-gray-700">Số ngày đi làm:</label>
                    <input type="number" id="so_ngay_di_lam" name="so_ngay_di_lam" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           value="<?= $editData ? htmlspecialchars($editData['so_ngay_di_lam']) : '' ?>">
                </div>

                <div class="flex flex-col">
                    <label for="so_ngay_nghi_co_phep" class="mb-2 text-sm font-medium text-gray-700">Số ngày nghỉ có phép:</label>
                    <input type="number" id="so_ngay_nghi_co_phep" name="so_ngay_nghi_co_phep" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           value="<?= $editData ? htmlspecialchars($editData['so_ngay_nghi_co_phep']) : '' ?>">
                </div>

                <div class="flex flex-col">
                    <label for="so_ngay_nghi_khong_phep" class="mb-2 text-sm font-medium text-gray-700">Số ngày nghỉ không phép:</label>
                    <input type="number" id="so_ngay_nghi_khong_phep" name="so_ngay_nghi_khong_phep" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           value="<?= $editData ? htmlspecialchars($editData['so_ngay_nghi_khong_phep']) : '' ?>">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-6">
                <button type="submit" name="submit"
        class="px-6 py-3 rounded-full text-white font-semibold 
               bg-gradient-to-r from-cyan-500 to-blue-600 
               hover:from-cyan-600 hover:to-blue-700 transition">
    <?= $editData ? 'Cập nhật' : 'Thêm mới' ?>
</button>

                <?php if ($editData): ?>
                    <a href="index.php?controller=chamcong&action=index"
                       class="px-6 py-3 rounded-full text-[var(--primary)] bg-gray-200 font-semibold hover:bg-gray-300">
                        Hủy
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Bảng danh sách -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-[var(--primary)] text-white">
                    <tr>
                        <th class="p-4 font-semibold text-left rounded-tl-xl">STT</th>
                        <th class="p-4 font-semibold text-left">Nhân viên</th>
                        <th class="p-4 font-semibold text-left">Tháng</th>
                        <th class="p-4 font-semibold text-left">Năm</th>
                        <th class="p-4 font-semibold text-left">Số ngày đi làm</th>
                        <th class="p-4 font-semibold text-left">Nghỉ có phép</th>
                        <th class="p-4 font-semibold text-left">Nghỉ không phép</th>
                        <?php if ($isAdmin): ?>
                            <th class="p-4 font-semibold text-left rounded-tr-xl">Hành động</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($chamcongList)): ?>
                        <?php $stt = 1; ?>
                        <?php foreach ($chamcongList as $cc): ?>
                            <tr class="border-b last:border-b-0 even:bg-gray-50 hover:bg-[var(--secondary)] transition-colors duration-200">
                                <td class="p-4 text-gray-800"><?= $stt++ ?></td>
                                <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['ho_ten']) ?></td>
                                <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['thang']) ?></td>
                                <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['nam']) ?></td>
                                <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['so_ngay_di_lam']) ?></td>
                                <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['so_ngay_nghi_co_phep']) ?></td>
                                <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['so_ngay_nghi_khong_phep']) ?></td>
                                <?php if ($isAdmin): ?>
                                    <td class="p-4">
                                        <a href="index.php?controller=chamcong&action=index&edit=<?= htmlspecialchars($cc['id_chamcong']) ?>"
   class="text-blue-600 font-semibold hover:text-blue-800 transition">
   📝 Sửa
</a>


                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 8 : 7 ?>" class="p-4 text-center text-gray-500">Chưa có dữ liệu chấm công nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    const alertMessage = document.getElementById('alert-message');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.opacity = '0';
            setTimeout(() => { alertMessage.remove(); }, 500);
        }, 5000);
    }
</script>

<?php @include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>
