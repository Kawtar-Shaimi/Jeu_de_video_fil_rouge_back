<?php
namespace Core;

interface DataSource
{
    public function getDsn(): string;
    public function getUsername(): string;
    public function getPassword(): string;
}
