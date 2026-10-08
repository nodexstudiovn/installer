<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * CLI Command: nodex new <project-name>
 */

namespace NodeX\Installer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class NewCommand extends Command
{
    protected static $defaultName = 'new';

    protected function configure(): void
    {
        $this
            ->setName('new')
            ->setDescription('Khởi tạo một dự án NodeX Studio Framework v1.1.0 mới')
            ->addArgument('name', InputArgument::REQUIRED, 'Tên thư mục dự án của bạn');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectName = (string)$input->getArgument('name');
        $targetPath = getcwd() . '/' . $projectName;

        if (is_dir($targetPath)) {
            $output->writeln("<error> Thư mục dự án '{$projectName}' đã tồn tại! Vui lòng chọn một tên khác.</error>");
            return Command::FAILURE;
        }

        $output->writeln("\n<info>⚡ Đang khởi tạo dự án NodeX Studio Framework v1.1.0: {$projectName}...</info>\n");

        // 1. Tải bản sao mã nguồn NodeX Studio Framework
        $repoUrl = 'https://github.com/nodexstudio/framework.git';
        
        $output->writeln("<comment>[1/4] Đang clone bộ khung mã nguồn NodeX Studio từ Repository...</comment>");
        $gitProcess = new Process(['git', 'clone', '--depth=1', $repoUrl, $targetPath]);
        $gitProcess->run();

        // Fallback: Nếu git clone không thành công (VD offline hoặc repo riêng tư), sao chép trực tiếp từ bộ khung địa phương
        if (!$gitProcess->isSuccessful()) {
            $localTemplate = dirname(__DIR__, 3);
            if (is_dir($localTemplate) && file_exists($localTemplate . '/composer.json')) {
                $output->writeln("<comment>[1/4] Đang khởi tạo dự án từ NodeX Studio Local Master Template...</comment>");
                $this->copyDirectory($localTemplate, $targetPath);
            } else {
                $output->writeln("<error>✘ Không thể tải xuống mã nguồn framework. Vui lòng kiểm tra lại kết nối mạng hoặc Git.</error>");
                return Command::FAILURE;
            }
        }

        // 2. Dọn dẹp thư mục .git cũ và thư mục tạm
        $output->writeln("<comment>[2/4] Đang dọn dẹp cấu hình Git và nạp môi trường .env...</comment>");
        $this->removeDirectory($targetPath . '/.git');

        // Tạo file .env từ .env.example
        if (file_exists($targetPath . '/.env.example')) {
            copy($targetPath . '/.env.example', $targetPath . '/.env');
        }

        // 3. Chạy composer install
        $output->writeln("<comment>[3/4] Đang cài đặt các phụ thuộc Composer...</comment>");
        $composerProcess = new Process(['composer', 'install'], $targetPath);
        $composerProcess->setTimeout(null);
        $composerProcess->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        // 4. Hoàn tất thông báo
        $output->writeln("\n<info>✨ CHÚC MỪNG! Dự án {$projectName} đã được tạo thành công!</info>");
        $output->writeln("<comment>Bắt đầu phát triển dự án với các câu lệnh sau:</comment>\n");
        $output->writeln("  <info>cd {$projectName}</info>");
        $output->writeln("  <info>php nodex serve</info>       <comment># Khởi chạy Local Development Server (Port 8000)</comment>");
        $output->writeln("  <info>npm run dev</info>          <comment># Khởi chạy Vite Hot Module Reloading</comment>\n");

        return Command::SUCCESS;
    }

    /**
     * Xóa thư mục đệ quy
     */
    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir) ?: [], ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : @unlink($path);
        }

        @rmdir($dir);
    }

    /**
     * Sao chép thư mục đệ quy
     */
    private function copyDirectory(string $src, string $dst): void
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if ($file !== '.' && $file !== '..' && $file !== '.git' && $file !== 'node_modules' && $file !== 'vendor' && $file !== 'installer') {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
